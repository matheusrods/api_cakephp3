<?php

namespace App\Controller\Api;

use App\Controller\Api\PosApiController as ApiController;
use App\Utils\Comum;
use App\Form\ObsObservacoesSalvarForm as salvarValidation;
use Cake\Log\Log;

use Exception;

class PosObsObservacoesController extends ApiController
{

    public $PosObs = null;

    public function initialize()
    {
        parent::initialize();

        $this->PosObs = $this->loadModel("PosObs");

        $this->Auth->allow();
    }

    /**
     * Observador EHS - Salvar uma observação
     *
     * @return string|Exception
     */
    public function salvar($codigo_observacao = null)
    {
        $this->request->allowMethod(['POST', 'PUT']);
        $this->loadModel("Usuario");

        $formData = $this->request->getData();

        if ($this->request->is('put')) {
            $formData['codigo_observacao'] = $codigo_observacao;
        };

        try {

            //Verificação para pegar usuários sem um cpf válido para inserir em usuario_observador
            //impossibilitando a não exibição da observação
            $observadores = $formData['observadores'];

            if (!empty($observadores)) {

                $qtd_erros = 0;
                foreach ($observadores as $obs) {

                    $usuario = $this->Usuario->find()
                        ->select(["apelido"])
                        ->where(["codigo" => $obs['codigo_usuario']])
                        ->enableHydration(false)
                        ->first();

                    if (empty($usuario) || !Comum::validarCPF($usuario['apelido'])) {
                        $qtd_erros++;
                    }
                }

                if ($qtd_erros > 0) {
                    return $this->responseMessage('CPF do observador é inválido.');
                }
            }

            $ObsForm = new salvarValidation(); // valida o payload completo

            // não permite prosseguir até o banco se a entidade for inválida
            if (!$ObsForm->validate($formData)) {
                return $this->responseMessage('Erro ao tentar gravar uma Observação', $ObsForm->getErrors());
            }

            // resolvo a gravação na model PosObs, principal do Observador EHS
            $tableData = $this->PosObs->salvarObservacao($formData);

            // se a consulta retornou com registros
            if ($tableData) {

                return $this->responseMessage('Observação registrada com sucesso!');
            }

            // se a consulta retornou vazia
            throw new Exception('Não foi encontrado dados referente ao código informado.', self::$POS_ERROR_CODE);
        } catch (Exception $e) {
            $data = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            $this->set(compact('data'));
        }
    }


    /**
     * Observador EHS - Obter lista de tipos para observação
     *
     * @param int $codigo_unidade
     * @param int $q
     * @param int $status
     *
     * @return array|Exception
     */
    public function obterLista()
    {
        $this->request->allowMethod(['GET']);

        $codigo_unidade = $this->request->getQuery('codigo_unidade');
        $status = $this->request->getQuery('status');
        //
        //
        /**
         * Filtrar - parâmetro "autor"
         * se "null" deve trazer Todos os registros
         * se "area" traz da própria área
         * se "usuario" traz do usuário logado
         */
        $autor = $this->request->getQuery('autor');
        $ate = $this->request->getQuery('periodo_ate');
        $de = $this->request->getQuery('periodo_de');
        $limit = $this->request->getQuery('limit');

        $filtrar = ['autor' => $autor, 'periodo_de' => $de, 'periodo_ate' => $ate, 'limit' => $limit];

        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade.', self::$POS_ERROR_CODE);
            }

            // valida se o código faz parte da relação do usuário autenticado
            if (!$this->validaCodigoClienteUsuarioAutenticado($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade relacionado ao usuário autenticado.', self::$POS_ERROR_CODE);
            }

            // prepara condições de pesquisa
            $filtros = ['codigo_unidade' => $codigo_unidade, 'filtrar' => $filtrar];

            if (empty($status)) {
                $filtros['status_in'] = [1, 2, 3, 4, 5, 6];
            } else {
                $filtros['status'] = $status;
            }

            $tableData = $this->PosObs->cteobterListaObservacoes($filtros);

            $data = $tableData;

            if (!empty($data)) {
                $this->set(compact('data'));
            } else {
                $this->responseMessage([]);
            }

            // se ResultSet retornou com registros
            // if ($tableData->count() > 0) {
            //     debug('opa');exit;

            //     return $this->set(compact('data'));
            //     // return $this->responseMessage($tableData);
            // }


            // debug('passei');exit;

            // se a consulta retornou vazia
            // throw new Exception('Não foi encontrado registros referente ao código informado.', self::$POS_ERROR_CODE);

        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }

    public function obterListaTimer()
    {
        $this->request->allowMethod(['GET']);

        $codigo_unidade = $this->request->getQuery('codigo_unidade');
        $status = $this->request->getQuery('status');
        //
        //
        /**
         * Filtrar - parâmetro "autor"
         * se "null" deve trazer Todos os registros
         * se "area" traz da própria área
         * se "usuario" traz do usuário logado
         */
        $autor = $this->request->getQuery('autor');
        $ate = $this->request->getQuery('periodo_ate');
        $de = $this->request->getQuery('periodo_de');
        $limit = $this->request->getQuery('limit');

        $filtrar = ['autor' => $autor, 'periodo_de' => $de, 'periodo_ate' => $ate, 'limit' => $limit];

        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade.', self::$POS_ERROR_CODE);
            }

            // valida se o código faz parte da relação do usuário autenticado
            if (!$this->validaCodigoClienteUsuarioAutenticado($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade relacionado ao usuário autenticado.', self::$POS_ERROR_CODE);
            }

            // prepara condições de pesquisa
            $filtros = ['codigo_unidade' => $codigo_unidade, 'filtrar' => $filtrar];

            if (empty($status)) {
                $filtros['status_in'] = [1, 2, 3, 4, 5, 6];
            } else {
                $filtros['status'] = $status;
            }

            $tableData = $this->PosObs->cteobterListaObservacoesTimer($filtros);

            $data = $tableData;

            if (!empty($data)) {
                $this->set(compact('data'));
            } else {
                $this->responseMessage([]);
            }

            // se ResultSet retornou com registros
            // if ($tableData->count() > 0) {
            //     debug('opa');exit;

            //     return $this->set(compact('data'));
            //     // return $this->responseMessage($tableData);
            // }


            // debug('passei');exit;

            // se a consulta retornou vazia
            // throw new Exception('Não foi encontrado registros referente ao código informado.', self::$POS_ERROR_CODE);

        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }

    /**
     * Observador EHS - Obter lista de tipos para observação
     *
     * @param int $codigo_unidade
     * @param int $q
     * @param int $status
     *
     * @return array|Exception
     */
    public function obterListagem()
    {
        $this->request->allowMethod(['GET']);

        $codigo_unidade = $this->request->getQuery('codigo_unidade');
        $status = $this->request->getQuery('status');
        //
        //
        /**
         * Filtrar - parâmetro "autor"
         * se "null" deve trazer Todos os registros
         * se "area" traz da própria área
         * se "usuario" traz do usuário logado
         */
        $autor = $this->request->getQuery('autor');
        $ate = $this->request->getQuery('periodo_ate');
        $de = $this->request->getQuery('periodo_de');
        $limit = $this->request->getQuery('limit');

        $filtrar = ['autor' => $autor, 'periodo_de' => $de, 'periodo_ate' => $ate, 'limit' => $limit];

        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade.', self::$POS_ERROR_CODE);
            }

            // valida se o código faz parte da relação do usuário autenticado
            if (!$this->validaCodigoClienteUsuarioAutenticado($codigo_unidade)) {
                throw new Exception('É obrigatório informar um código de unidade relacionado ao usuário autenticado.', self::$POS_ERROR_CODE);
            }

            // prepara condições de pesquisa
            $filtros = ['codigo_unidade' => $codigo_unidade, 'filtrar' => $filtrar];

            if (empty($status)) {
                $filtros['status_in'] = [1, 2, 3, 4, 5, 6];
            } else {
                $filtros['status'] = $status;
            }

            $tableData = $this->PosObs->obterListagem($filtros);

            $data = $tableData;

            if (!empty($data)) {
                $this->set(compact('data'));
            } else {
                $this->responseMessage([]);
            }

            // se ResultSet retornou com registros
            // if ($tableData->count() > 0) {
            //     debug('opa');exit;

            //     return $this->set(compact('data'));
            //     // return $this->responseMessage($tableData);
            // }


            // debug('passei');exit;

            // se a consulta retornou vazia
            // throw new Exception('Não foi encontrado registros referente ao código informado.', self::$POS_ERROR_CODE);

        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }

    /**
     * Observador EHS - Cancelar uma observação
     *
     * @param int $codigo_pos_observacao
     *
     * @return array|Exception
     */
    public function cancelar(int $codigo_pos_observacao = null)
    {
        $this->request->allowMethod(['DELETE']);

        $formData = $this->request->getData();
        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_pos_observacao)) {
                throw new Exception('É obrigatório informar um código de observação.', self::$POS_ERROR_CODE);
            }

            if (!isset($formData['justificativa'])) {
                throw new Exception('É obrigatório informar parâmetro de justificativa.', self::$POS_ERROR_CODE);
            }

            $tableData = $this->PosObs->cancelarObservacao($codigo_pos_observacao, (array)$formData);

            // se a consulta retornou com sucesso
            if ($tableData) {
                return $this->responseMessage('Cancelamento da Observação registrado com sucesso');
            }

            // se a consulta retornou vazia
            throw new Exception("Não foi possível cancelar a Observação", self::$POS_ERROR_CODE);
        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }


    /**
     * Observador EHS - Obter uma observação
     *
     * @param int $codigo_pos_observacao
     *
     * @return array|Exception
     */
    public function obter(int $codigo_pos_observacao = null)
    {
        $this->request->allowMethod(['GET']);

        try {

            // valida se tem os parametros corretamente
            if (empty($codigo_pos_observacao)) {
                throw new Exception('É obrigatório informar um código de observação.', self::$POS_ERROR_CODE);
            }

            $tableData = $this->PosObs->obterObservacao($codigo_pos_observacao);

            // se a consulta retornou com sucesso
            if ($tableData) {
                return $this->responseMessage($tableData);
            }

            // se a consulta retornou vazia
            // throw new Exception('Não foi encontrado dados referente ao código informado.', self::$POS_ERROR_CODE);
            return $this->responseMessage([]);
        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }
}
