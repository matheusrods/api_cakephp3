<?php

namespace App\Controller\Api;

use Exception;

use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use App\Controller\Api\PosApiController as ApiController;
use App\Form\ObsClassificacoesRiscoSalvarForm;
use App\Form\ObsAcoesMelhoriasSalvarForm;

class PosObsClassificacoesRiscosController extends ApiController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('PosObsObservacaoAcaoMelhoria');
        $this->loadModel('PosObs');

        $this->connect = ConnectionManager::get('default');
        $this->Auth->allow();
    }

    /**
     * Observador EHS - Cria vínculo entre uma classificação de risco e uma observação
     *
     * @return string|Exception
     */
    public function salvar()
    {
        $this->request->allowMethod(['POST']);

        $formData = $this->request->getData();

        try {
            $obsValidador      = new ObsClassificacoesRiscoSalvarForm();
            $obsAcoesValidador = new ObsAcoesMelhoriasSalvarForm();

            if (!$obsValidador->validate($formData)) {
                return $this->responseMessage(
                    'Erro ao tentar gravar uma Classificação de Risco',
                    $obsValidador->getErrors()
                );
            }

            $acoesRegistro   = $formData['acoes_melhoria_registro'];
            $existeRegistros = (count($acoesRegistro) > 0);

            if ($existeRegistros) {
                foreach ($acoesRegistro as $acao) {
                    $formValido = $obsAcoesValidador->validate($acao);

                    if (!$formValido) {
                        return $this->responseMessage(
                            'Erro ao validar formato das Classificações de Risco que seriam registradas',
                            $obsAcoesValidador->getErrors()
                        );
                    }
                }
            }

            $tableData = $this->PosObs->salvarClassificacaoRisco($formData);

            if ($tableData) {
                return $this->responseMessage(
                    'Classificação de Risco registrada com sucesso!'
                );
            }

            throw new Exception('Não foi possível registrar uma Classificação de Risco.', self::$POS_ERROR_CODE);
        } catch (Exception $e) {
            return $this->responseMessage($e);
        }
    }
}
