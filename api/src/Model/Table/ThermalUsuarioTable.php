<?php
namespace App\Model\Table;

use App\Model\Table\UsuarioTable;
use Cake\ORM\TableRegistry;
use App\Utils\EncodingUtil;

/**
 * ThermalUsuario
 * 
 * Operações com usuario relativas ao aplicativo Thermal-Care
 * 
 */
class ThermalUsuarioTable extends UsuarioTable
{

    // Alias ThermalUsuarioTable
    // table usuario

    /**
     * Retorna o código da Matriz de acordo com o código do Usuário fornecido
     * 
     * @param int $codigo_usuario
     * @return array
     */
    public function obterCodigoMatrizPeloCodigoUsuario(int $codigo_usuario)
    {
        
        // obter alocações do codigo_usuario
        $usuario = $this->obterDadosDoUsuario($codigo_usuario);
        
        $codigo_cliente = null;
        $codigo_cliente_matriz = null;

        if($usuario->cliente[0]){
            $codigo_cliente = isset($usuario->cliente[0]['codigo']) ? $usuario->cliente[0]['codigo'] : null;
        }
        if(empty($codigo_cliente)){
            return null;
        }
        // se existir codigo_cliente buscar matriz
        $this->ThermalGruposEconomicos = TableRegistry::get('ThermalGruposEconomicos');
        $matrizData = $this->ThermalGruposEconomicos->obterCodigoMatrizPeloCodigoFilial($codigo_cliente);
        
        if(!empty($matrizData->toArray())){
            $codigo_cliente_matriz = isset($matrizData->toArray()[0]['codigo_cliente_matriz'])?$matrizData->toArray()[0]['codigo_cliente_matriz']:null;
        }

        return $codigo_cliente_matriz;

    }


    /**
     * Obter dados de um usuario fornecendo $codigo_usuario(primary key)
     *
     * @param integer $codigo_usuario
     * @return array
     */
    public function obterDadosDoUsuario(int $codigo_usuario)
    {
        if (empty($codigo_usuario)) {
            return ['error' => 'Código usuário requerido'];
        }

        if (strlen($codigo_usuario) < 11) {
            //monta as conditions com codigo
            $conditions = ['ThermalUsuario.codigo' => $codigo_usuario];
        } else {
            //monta as conditions com cpf
            $conditions = ['UsuariosDados.cpf' => $codigo_usuario];
        }

        $fields = [
            'codigo_usuario' => 'ThermalUsuario.codigo',
            'nome' => 'ThermalUsuario.nome',
            'email' => 'ThermalUsuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            // 'senha' => 'ThermalUsuario.senha',
            'notificacao' => "(CASE WHEN UsuarioSistema.codigo IS NOT NULL THEN 1 ELSE 0 END)",
            'cliente.codigo',
            'cliente.nome_fantasia',
            'cliente.razao_social',
            'contato_emergencia.nome',
            'contato_emergencia.telefone',
            'contato_emergencia.celular',
            'contato_emergencia.grau_parentesco',
            'contato_emergencia.email',
        ];

        //monta os joins
        $joins = [
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'LEFT',
                'conditions' => 'ThermalUsuario.codigo = UsuariosDados.codigo_usuario'
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'ThermalUsuario.codigo = UsuarioMultiCliente.codigo_usuario'
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'ThermalUsuario.codigo = UsuarioSistema.codigo_usuario'
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'ThermalUsuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente'
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'ThermalUsuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1'
            ],
        ];

        try {

            //executa os dados
            $dados = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->all()
                ->toArray();

        } catch (\Exception $th) {

            $msg_erro = json_encode($th->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDoUsuario:'.$msg_erro.')'];
        }


        $usuario = (isset($dados[0]) ? $dados[0] : $dados);
        $iconv = new EncodingUtil();

        foreach ($dados as $key => $dado) {

            if (!empty($dado['cliente']['codigo'])) {
                $usuario['cliente'][$key] = array(
                    'codigo' => $dado['cliente']['codigo'],
                    'nome_fantasia' => $iconv->convert($dado['cliente']['nome_fantasia']),
                    'razao_social' => $iconv->convert($dado['cliente']['razao_social']),
                );
            }

            if (!empty($dado['fornecedor']['codigo'])) {
                $usuario['fornecedor'][$key] = array(
                    'codigo' => $dado['fornecedor']['codigo'],
                    'nome_fantasia' => $iconv->convert($dado['fornecedor']['nome']),
                    'razao_social' => $iconv->convert($dado['fornecedor']['razao_social']),
                    'cnpj' => $iconv->convert($dado['fornecedor']['codigo_documento']),
                );
            }
        }

        unset($usuario['cliente']['codigo']);
        unset($usuario['cliente']['nome_fantasia']);
        unset($usuario['cliente']['razao_social']);
        unset($usuario['fornecedor']['codigo']);
        unset($usuario['fornecedor']['nome']);
        unset($usuario['fornecedor']['razao_social']);
        unset($usuario['fornecedor']['codigo_documento']);

        return $usuario;
    }

    

}
