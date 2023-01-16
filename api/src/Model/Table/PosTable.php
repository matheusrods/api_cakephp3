<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

use App\Utils\DatetimeUtil;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Collection\Collection;

use Cake\Utility\Security as Security;
use Firebase\JWT\JWT;

use Cake\Log\Log;

use Exception;

/**
 * Pos
 *
 * Operações comuns relativas aos aplicativos P.O.S.
 *
 */
class PosTable extends AppTable
{
    // Alias PosTable
    // table usuario

    /**
     * codigo padrão para o produto Plano de Ação
     *
     * @var integer
     */
    public static $CODIGO_POS_SISTEMA_PLANO_ACAO = 1;

    /**
     * codigo padrão para o produto Walk Talk
     *
     * @var integer
     */
    public static $CODIGO_POS_SISTEMA_WALK_TALK = 2;

    /**
     * codigo padrão para o produto Observador EHS
     *
     * @var integer
     */
    public static $CODIGO_POS_SISTEMA_OBSERVADOR = 3;


    /**
     * Sobre-escrito do AppTable para adicionar novos eventos para os produtos POS
     * e não influenciar outros apps como o Lyn
     *
     * valores padrão para salvar entidades
     *
     * @return bool
     */
    public function beforeSave(Event $event, Entity $entity, $options)
    {

        $codigo_usuario_autenticado = $this->obterCodigoUsuarioAutenticado();

        if ($entity->isNew()) {
            $entity->data_inclusao = date('Y-m-d H:i:s'); //(new DatetimeUtil())->now();
            $entity->ativo = 1;

            if (empty($entity->codigo_usuario_inclusao) && !empty($codigo_usuario_autenticado)) {
                $entity->codigo_usuario_inclusao = $codigo_usuario_autenticado;
            }
        } else {

            $entity->data_alteracao = date('Y-m-d H:i:s'); // (new DatetimeUtil())->now();

            if (!empty($codigo_usuario_autenticado)) {
                $entity->codigo_usuario_alteracao = $codigo_usuario_autenticado;
            }
        }

        return true;
    }

    /**
     * Retorna o código da Matriz de acordo com o código da Unidade fornecida
     *
     * @param int $codigo_unidade
     *
     * @return int|null
     */
    public function obterCodigoMatrizPeloCodigoFilial(int $codigo_unidade)
    {
        $codigo_cliente_matriz = null;

        try {

            $PosGruposEconomicos = TableRegistry::getTableLocator()->get('PosGruposEconomicos');
            $matrizData = $PosGruposEconomicos->obterCodigoMatrizPeloCodigoFilial($codigo_unidade);

            if (!empty($matrizData->toArray())) {
                $codigo_cliente_matriz = isset($matrizData->toArray()[0]['codigo_cliente_matriz']) ? $matrizData->toArray()[0]['codigo_cliente_matriz'] : null;
            }

            return $codigo_cliente_matriz;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Retorna o código da Matriz de acordo com o código do Usuário fornecido
     *
     * @param int $codigo_usuario
     *
     * @return int|null
     */
    public function obterCodigoMatrizPeloCodigoUsuario(int $codigo_usuario)
    {

        // obter alocações do codigo_usuario
        $usuario = $this->obterDadosDoUsuario($codigo_usuario);

        $codigo_cliente = null;

        if (isset($usuario->cliente[0])) {
            $codigo_cliente = isset($usuario->cliente[0]['codigo']) ? $usuario->cliente[0]['codigo'] : null;
        }
        if (empty($codigo_cliente)) {
            return null;
        }

        // se existir codigo_cliente buscar matriz
        return $this->obterCodigoMatrizPeloCodigoFilial($codigo_cliente);
    }

    /**
     * Obter registros usando Find e Grupo Economico
     *
     * codigo_cliente* é requerido em conditions
     * ativo = 1 será retornado como padrão se nao informado
     *
     * @param string $modelName
     * @param array $conditions
     * @param array $fields
     * @param array $joins
     * @param array $group
     * @param array $order
     * @param int|null $limit
     *
     * @return ORM/Query
     */
    public function findPorGrupoEconomico(
        string $modelName,
        array $conditions = [],
        array $fields = [],
        array $joins = [],
        array $group = [],
        array $order = [],
        $limit = null
    ) {

        try {

            if (isset($conditions['codigo_cliente'])) {
                // sobreescreve codigo_cliente para o da Matriz no grupo economico
                $conditions['codigo_cliente'] = $this->obterCodigoMatrizPeloCodigoFilial($conditions['codigo_cliente']);
            }

            $modelData = TableRegistry::getTableLocator()->get($modelName);

            return $modelData->find()
                ->select($fields)
                ->where($conditions)
                ->join($joins)
                ->group($group)
                ->order($order)
                ->limit($limit);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Obter registros de Categorias
     *
     * codigo_cliente é requerido em conditions
     * codigo_pos_ferramenta pode ser usado para filtrar por um dos produtos P.O.S
     *
     * @param array $conditions
     * @param array $fields
     * @param array $joins
     * @param array $group
     * @param array $order
     * @param int|null $limit
     *
     * @return ORM/Query
     */
    public function obterCategorias(
        array $conditions = [],
        array $fields = [],
        array $joins = [],
        array $group = [],
        array $order = [],
        $limit = null
    ) {

        try {

            return $this->findPorGrupoEconomico('PosCategorias', $conditions, $fields, $joins, $group, $order, $limit);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function obterDadosDoUsuario(int $codigo_usuario)
    {
        Log::debug(__METHOD__);

        try {

            if (empty($codigo_usuario)) {
                throw new Exception('Código do usuário não encontrado', 1);
            }

            $Usuario = TableRegistry::getTableLocator()->get('Usuario');

            return $Usuario->obterDadosDoUsuarioAlocacao($codigo_usuario);
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    public function obterAlocacoesDoUsuario(int $codigo_usuario = null)
    {
        Log::debug(__METHOD__);

        try {

            if (empty($codigo_usuario)) {
                throw new Exception('Código do usuário não encontrado', 1);
            }

            $alocacoes = $this->obterDadosDoUsuario($codigo_usuario);

            Log::debug($alocacoes);

            if (empty($alocacoes->cliente)) {
                throw new Exception('Alocações não encontradas para este usuário', 1);
            }

            $coll = new Collection($alocacoes->cliente);

            if ($coll->count() > 0) {
                return $coll->extract('codigo')->toList();
            }

            throw new Exception('Alocações não encontradas para este usuário', 1);
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    public function obterCodigoUsuarioAutenticado()
    {
        Log::debug(__METHOD__);

        $codigo_usuario_autenticado = null;

        $headers = Router::getRequest()->getHeaders();

        if (isset($headers['Authorization'][0])) {

            $token = substr($headers['Authorization'][0], 7);
            $jwt_codificacao = array("typ" => "JWT", "alg" => "HS256");
            $dados = JWT::decode($token, Security::getSalt(), $jwt_codificacao);
            if (isset($dados->codigo_usuario)) {
                $codigo_usuario_autenticado = $dados->codigo_usuario;
            }
        }

        return $codigo_usuario_autenticado;
    }

    public function obterAlocacoesUsuarioAutenticado()
    {

        Log::debug(__METHOD__);

        try {

            $codigo_usuario = $this->obterCodigoUsuarioAutenticado();

            if (empty($codigo_usuario)) {
                throw new Exception('Usuário não autenticado');
            }

            return $this->obterAlocacoesDoUsuario($codigo_usuario);
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    /**
     * Obter codigo_setor e codigo_cargo do usuario autenticado
     *
     * "FuncionarioSetorCargo": {
     *  "codigo_cliente": null,
     *  "codigo_setor": null,
     *  "codigo_cargo": null
     * },
     *
     * @return mixed
     */
    public function obterSetorCargoUsuarioAutenticado()
    {

        Log::debug(__METHOD__);

        try {

            $codigo_usuario = $this->obterCodigoUsuarioAutenticado();

            if (empty($codigo_usuario)) {
                throw new Exception('Usuário não autenticado');
            }

            $FuncionarioSetorCargo = $this->obterDadosDoUsuario($codigo_usuario);

            Log::debug($FuncionarioSetorCargo);

            return $FuncionarioSetorCargo;
            // if(empty($FuncionarioSetorCargo)){
            //     throw new Exception('Informação Setor e Cargo não encontrado para este usuário',1);
            // }

        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    /**
     * Valida se a pesquisa de um cliente faz parte das alocações dele
     * verifica se codigo_cliente fornecido encontra-se nos códigos de alocação disponíveis para o usuário autenticado
     *
     * @param int $codigo_cliente
     * @return void
     */
    public function validaCodigoClienteUsuarioAutenticado($codigo_cliente)
    {

        Log::debug(__METHOD__);

        try {

            if (empty($codigo_cliente)) {
                throw new Exception('Código cliente não fornecido');
            }

            $arrLocacoes = $this->obterAlocacoesUsuarioAutenticado($codigo_cliente);
            $collection = new Collection($arrLocacoes);
            $hasCodigoCliente = $collection->some(function ($clientes) use ($codigo_cliente) {
                return intVal($clientes) == intVal($codigo_cliente);
            });

            return $hasCodigoCliente;
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }


    /**
     * Função padrão para criar ou alterar
     *
     * @param integer $codigo
     * @param array $data
     * @param string $message_token
     * @return Entity|Exception
     */
    public function salvar(int $codigo = null, array $data = [], string $message_token = null)
    {

        Log::debug(__METHOD__);

        $editMode = !empty($codigo) ?? false;

        try {
            $message_token = !empty($codigo) ? $message_token : $this->getAlias();

            if ($editMode) {

                $findData = $this->find()->where([
                    'codigo' => $codigo
                ])->first();

                if (empty($findData)) {
                    throw new Exception(sprintf("Registros %s não encontrado para o codigo %s", $message_token, $codigo), 1);
                }

                $obsEntity = $this->patchEntity($findData, $data);
                // ** Não precisa mais pois esta no beforeSave $obsEntity->set(['codigo_usuario_alteracao' => $this->obterCodigoUsuarioAutenticado()]);
                // ** Não precisa mais pois esta no beforeSave $obsEntity->set(['data_alteracao' => date('Y-m-d H:i:s')]);
            } else {
                $obsEntity = $this->newEntity($data);
                $obsEntity->set(['codigo_usuario_inclusao' => $this->obterCodigoUsuarioAutenticado()]);
                // ** Não precisa mais pois esta no beforeSave $obsEntity->set(['ativo' => 1]);
            }

            if ($obsEntity->hasErrors()) {
                Log::debug($obsEntity->getErrors());
                throw new Exception(sprintf("Ocorreu erro com %s, não foi possível registrar", $message_token), 1);
            }

            if (!$this->save($obsEntity)) {
                throw new Exception(sprintf("Ocorreu erro com %s, não foi possível registrar", $message_token), 1);
            }

            return $obsEntity;
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    public static function soNumero($documento)
    {
        $v = preg_replace('/\D/', '', $documento);
        return !empty($v) ? intval($v) : null;
    }

    function arrayToObject($array)
    {

        $object = new \stdClass();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->arrayToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
}
