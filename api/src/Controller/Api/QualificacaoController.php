<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Controller\AppController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;

/**
 * Qualificacao Controller
 *
 * @property \App\Model\Table\QualificacaoTable $Qualificacao
 *
 * @method \App\Model\Entity\Qualificacao[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QualificacaoController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');
        $this->loadModel("ArrtpaRi");
        $this->loadModel("MetodosTipo");
        $this->loadModel("EquipamentosAdotados");
        $this->loadModel("EquipamentosInspecaoTipo");
        $this->loadModel("FerramentasAnaliseTipo");
        $this->loadModel("FerramentasAnalise");
        $this->loadModel("UnidadesMedicao");
        $this->loadModel("Aprho");
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $qualificacao = $this->paginate($this->Qualificacao);

        $this->set(compact('qualificacao'));
    }

    public function getMetodosTipo()
    {
        $data = $this->MetodosTipo->find()->select(['codigo', 'descricao']);

        $this->set(compact('data'));
    }

    /**
     * FerramentasAnaliseTipo
     * POST, PUT method
     */
    public function postPutFerramentasAnaliseTipo()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de FerramentasAnaliseTipo é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getFerramentasAnaliseTipo = $this->FerramentasAnaliseTipo->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getFerramentasAnaliseTipo)) {
                    $error = 'Não foi encontrado FerramentasAnaliseTipo para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityFerramentasAnaliseTipo = $this->FerramentasAnaliseTipo->patchEntity($getFerramentasAnaliseTipo, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityFerramentasAnaliseTipo = $this->FerramentasAnaliseTipo->newEntity($dados);
            }

            //salva os dados
            if (!$this->FerramentasAnaliseTipo->save($entityFerramentasAnaliseTipo)) {
                $data['message'] = 'Erro ao inserir em FerramentasAnaliseTipo';
                $data['error'] = $entityFerramentasAnaliseTipo->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityFerramentasAnaliseTipo;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * FerramentasAnaliseTipo
     * GET method
     */
    public function getFerramentasAnaliseTipo($codigo_cliente)
    {
        $data = $this->FerramentasAnaliseTipo->find()->select(
            ['codigo', 'codigo_metodo_tipo', 'ferramenta_analise_categoria', 'descricao', 'versao', 'ferramenta_analise_form', 'ferramenta_analise_regras', 'codigo_cliente']
        )->where(['codigo_cliente' => $codigo_cliente])->toArray();

        $this->set(compact('data'));
    }

    /**
     * FerramentasAnaliseTipo
     * DELETE method
     */
    public function deleteFerramentasAnaliseTipo($codigo)
    {
        $getFerramentasAnaliseTipo = $this->FerramentasAnaliseTipo->find()->where(['codigo' => $codigo])->first();

        if (empty($getFerramentasAnaliseTipo)) {
            $error = 'FerramentasAnaliseTipo não encontrada!';
            $this->set(compact('error'));
            return;
        }

        $getFerramentasAnalise = $this->FerramentasAnalise->find()->where(['codigo_ferramenta_analise_tipo' => $codigo])->first();

        if (!empty($getFerramentasAnalise)) {
            $error = 'Não pode remover, pois exitem Ferramentas de analise vinculadas a esse codigo!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->FerramentasAnaliseTipo->delete($getFerramentasAnaliseTipo)) {
            $error = 'Erro ao remover a FerramentasAnaliseTipo!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "FerramentasAnaliseTipo removida com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * UnidadesMedicao
     * POST, PUT method
     */
    public function postPutUnidadeMedicao()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de UnidadesMedicao é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getUnidadesMedicao = $this->UnidadesMedicao->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getUnidadesMedicao)) {
                    $error = 'Não foi encontrado UnidadesMedicao para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityUnidadesMedicao = $this->UnidadesMedicao->patchEntity($getUnidadesMedicao, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityUnidadesMedicao = $this->UnidadesMedicao->newEntity($dados);
            }

            //salva os dados
            if (!$this->UnidadesMedicao->save($entityUnidadesMedicao)) {
                $data['message'] = 'Erro ao inserir em UnidadesMedicao';
                $data['error'] = $entityUnidadesMedicao->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityUnidadesMedicao;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * UnidadesMedicao
     * GET method
     */
    public function getUnidadesMedicao()
    {
        $data = $this->UnidadesMedicao->find()->select(['codigo', 'descricao', 'inteiro']);

        $this->set(compact('data'));
    }

    /**
     * UnidadesMedicao
     * DELETE method
     */
    public function deleteUnidadesMedicao($codigo)
    {
        $getUnidadesMedicao = $this->UnidadesMedicao->find()->where(['codigo' => $codigo])->first();

        if (empty($getUnidadesMedicao)) {
            $error = 'UnidadesMedicao não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->UnidadesMedicao->delete($getUnidadesMedicao)) {
            $error = 'Erro ao remover a UnidadesMedicao!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "UnidadesMedicao removida com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * EquipamentosInspecaoTipo
     * POST, PUT method
     */
    public function postPutEquipamentosInspecaoTipo()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de EquipamentosInspecaoTipo é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getEquipamentosInspecaoTipo = $this->EquipamentosInspecaoTipo->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getEquipamentosInspecaoTipo)) {
                    $error = 'Não foi encontrado EquipamentosInspecaoTipo para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityEquipamentosInspecaoTipo = $this->EquipamentosInspecaoTipo->patchEntity($getEquipamentosInspecaoTipo, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityEquipamentosInspecaoTipo = $this->EquipamentosInspecaoTipo->newEntity($dados);
            }

            //salva os dados
            if (!$this->EquipamentosInspecaoTipo->save($entityEquipamentosInspecaoTipo)) {
                $data['message'] = 'Erro ao inserir em EquipamentosInspecaoTipo';
                $data['error'] = $entityEquipamentosInspecaoTipo->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityEquipamentosInspecaoTipo;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * EquipamentosInspecaoTipo
     * GET method
     */
    public function getEquipamentosInspecaoTipo($codigo_cliente)
    {
        $data = $this->EquipamentosInspecaoTipo->find()->select(['codigo', 'descricao',
            'codigo_unidade_medicao', 'valor', 'limite_tolerancia', 'codigo_cliente'])
            ->where(['codigo_cliente' => $codigo_cliente]);

        $this->set(compact('data'));
    }

    /**
     * EquipamentosInspecaoTipo
     * DELETE method
     */
    public function deleteEquipamentosInspecaoTipo($codigo)
    {
        $getEquipamentosInspecaoTipo = $this->EquipamentosInspecaoTipo->find()->where(['codigo' => $codigo])->first();

        if (empty($getEquipamentosInspecaoTipo)) {
            $error = 'EquipamentosInspecaoTipo não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->EquipamentosInspecaoTipo->delete($getEquipamentosInspecaoTipo)) {
            $error = 'Erro ao remover a EquipamentosInspecaoTipo!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "EquipamentosInspecaoTipo removido com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * EquipamentosAdotados
     * POST, PUT method
     */
    public function postPutEquipamentosAdotados()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de EquipamentosAdotados é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getEquipamentosAdotados = $this->EquipamentosAdotados->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getEquipamentosAdotados)) {
                    $error = 'Não foi encontrado EquipamentosAdotados para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityEquipamentosAdotados = $this->EquipamentosAdotados->patchEntity($getEquipamentosAdotados, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityEquipamentosAdotados = $this->EquipamentosAdotados->newEntity($dados);
            }

            //salva os dados
            if (!$this->EquipamentosAdotados->save($entityEquipamentosAdotados)) {
                $data['message'] = 'Erro ao inserir em EquipamentosAdotados';
                $data['error'] = $entityEquipamentosAdotados->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityEquipamentosAdotados;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * EquipamentosAdotados
     * GET method
     */
    public function getEquipamentosAdotados()
    {
        $data = $this->EquipamentosAdotados->find()->select(
            ['codigo', 'resultados', 'codigo_aprho', 'codigo_equipamento_inspecao_tipo', 'codigo_unidade_medicao', 'valor', 'limite_tolerancia']
        );

        $this->set(compact('data'));
    }

    /**
     * EquipamentosAdotados
     * DELETE method
     */
    public function deleteEquipamentosAdotados($codigo)
    {
        $getEquipamentosAdotados = $this->EquipamentosAdotados->find()->where(['codigo' => $codigo])->first();

        if (empty($getEquipamentosAdotados)) {
            $error = 'EquipamentosAdotados não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->EquipamentosAdotados->delete($getEquipamentosAdotados)) {
            $error = 'Erro ao remover a EquipamentosAdotados!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "EquipamentosAdotados removida com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * Qualificacao
     * POST, PUT method
     */
    public function postPutQualificacao()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            // Verifica se codigos dos campos informados realmente existem
            $codigo_arrtpa_ri = $this->ArrtpaRi->find()->where(['codigo' => $dados['codigo_arrtpa_ri']])->first();
            $codigo_metodo_tipo = $this->MetodosTipo->find()->where(['codigo' => $dados['codigo_metodo_tipo']])->first();

            if (empty($codigo_arrtpa_ri)) {
                $error = 'Campo codigo_arrtpa_ri informado não encontrado';
                $this->set(compact('error'));
                return;
            }

            if (empty($codigo_metodo_tipo)) {
                $error = 'Campo codigo_metodo_tipo informado não encontrado';
                $this->set(compact('error'));
                return;
            }

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de Qualificacao é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getQualificacao = $this->Qualificacao->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getQualificacao)) {
                    $error = 'Não foi encontrado Qualificacao para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityQualificacao = $this->Qualificacao->patchEntity($getQualificacao, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityQualificacao = $this->Qualificacao->newEntity($dados);
            }

            //salva os dados
            if (!$this->Qualificacao->save($entityQualificacao)) {
                $data['message'] = 'Erro ao inserir em Qualificacao';
                $data['error'] = $entityQualificacao->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityQualificacao;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * Qualificacao
     * GET method
     */
    public function getQualificacao($codigo_arrtpa_ri = null)
    {

        if (empty($codigo_arrtpa_ri)) {
            $data = $this->Qualificacao->find()
                ->select([
                    'codigo',
                    'codigo_arrtpa_ri',
                    'qualitativo',
                    'quantitativo',
                    'codigo_metodo_tipo',
                    'acidente_registrado',
                    'partes_afetadas',
                    'resultado_ponderacao'
                ])->toArray();
        } else {
            $data = $this->Qualificacao->find()
                ->select([
                    'codigo',
                    'codigo_arrtpa_ri',
                    'qualitativo',
                    'quantitativo',
                    'codigo_metodo_tipo',
                    'acidente_registrado',
                    'partes_afetadas',
                    'resultado_ponderacao'
                ])
                ->where(['codigo_arrtpa_ri' => $codigo_arrtpa_ri]);
        }

        $this->set(compact('data'));
    }

    /**
     * FerramentasAnalise
     * POST, PUT method
     */
    public function postPutFerramentasAnalise()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            $getFerramentasAnaliseTipo = $this->FerramentasAnaliseTipo->find()->where(['codigo' => $dados['codigo_ferramenta_analise_tipo']])->first();

            if (empty($getFerramentasAnaliseTipo)) {
                $error = 'Não foi encontrado FerramentasAnaliseTipo para o codigo informado!';
                $this->set(compact('error'));
                return;
            }

            $getQualificacao = $this->Qualificacao->find()->where(['codigo' => $dados['codigo_qualificacao']])->first();

            if (empty($getQualificacao)) {
                $error = 'Não foi encontrado Qualificacao para o codigo informado!';
                $this->set(compact('error'));
                return;
            }

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de FerramentasAnalise é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getFerramentasAnalise = $this->FerramentasAnalise->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getFerramentasAnalise)) {
                    $error = 'Não foi encontrado FerramentasAnalise para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');

                $entityFerramentasAnalise = $this->FerramentasAnalise->patchEntity($getFerramentasAnalise, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;

                $entityFerramentasAnalise = $this->FerramentasAnalise->newEntity($dados);
            }

            //salva os dados
            if (!$this->FerramentasAnalise->save($entityFerramentasAnalise)) {
                $data['message'] = 'Erro ao inserir em FerramentasAnalise';
                $data['error'] = $entityFerramentasAnalise->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityFerramentasAnalise;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * FerramentasAnalise
     * GET method
     */
    public function getFerramentasAnalise()
    {
        $data = $this->FerramentasAnalise->find()->select(['codigo', 'codigo_qualificacao', 'codigo_ferramenta_analise_tipo', 'ferramenta_analise_resultado', 'ferramenta_analise_level']);

        $this->set(compact('data'));
    }

    /**
     * FerramentasAnalise
     * DELETE method
     */
    public function deleteFerramentasAnalise($codigo)
    {
        $getFerramentasAnalise = $this->FerramentasAnalise->find()->where(['codigo' => $codigo])->first();

        if (empty($getFerramentasAnalise)) {
            $error = 'FerramentasAnalise não encontrada!';
            $this->set(compact('error'));
            return;
        }

        if (!$this->FerramentasAnalise->delete($getFerramentasAnalise)) {
            $error = 'Erro ao remover a FerramentasAnalise!';
            $this->set(compact('error'));
            return;
        }

        $data['Message'] = "FerramentasAnalise removida com sucesso!";

        $this->set(compact('data'));

    }

    /**
     * APRHO
     * POST, PUT method
     */
    public function postPutAprho()
    {

        $this->request->allowMethod(['post', 'put']); // aceita apenas POST / PUT

        //Abre a transação
        $this->connect->begin();

        try {

            $data = array();

            //pega os dados que veio do post
            $dados = $this->request->getData();

            $codigo_usuario_logado = $this->getAuthUser();

            // Verifica se codigos dos campos informados realmente existem
            $codigo_qualificacao = $this->Qualificacao->find()->where(['codigo' => $dados['codigo_qualificacao']])->first();

            if (empty($codigo_qualificacao)) {
                $error = 'Campo codigo_qualificacao informado não encontrado';
                $this->set(compact('error'));
                return;
            }

            //Função para upload de foto
            $foto = "";

            if (!empty($dados['foto'])) {

                //monta o array para enviar
                $dados_foto = array(
                    'file'   => $dados['foto'],
                    'prefix' => 'seguranca',
                    'type'   => 'base64'
                );

                //url de imagem
                $url_imagem = Comum::sendFileToServer($dados_foto);
                //pega o caminho da imagem
                $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

                if (!empty($caminho_image)) {

                    //seta o valor para a imagem que esta sendo criada
                    $foto = FILE_SERVER . $caminho_image['path'];
                }
            }

            if ($this->request->is(['put'])) {

                if (empty($dados['codigo'])) {
                    $error = 'Código de Aprho é necessário!';
                    $this->set(compact('error'));
                    return;
                }

                $getAprho = $this->Aprho->find()->where(['codigo' => $dados['codigo']])->first();

                if (empty($getAprho)) {
                    $error = 'Não foi encontrado Qualificacao para o codigo informado!';
                    $this->set(compact('error'));
                    return;
                }

                $dados['codigo_usuario_alteracao'] = $codigo_usuario_logado;
                $dados['data_alteracao'] = date('Y-m-d H:i:s');
                $dados['arquivo_url'] = $foto;

                $entityAprho = $this->Aprho->patchEntity($getAprho, $dados);

            } else {

                $dados['codigo_usuario_inclusao'] = $codigo_usuario_logado;
                $dados['arquivo_url'] = $foto;

                $entityAprho = $this->Aprho->newEntity($dados);
            }

            //salva os dados
            if (!$this->Aprho->save($entityAprho)) {
                $data['message'] = 'Erro ao inserir em Aprho';
                $data['error'] = $entityAprho->errors();
                $this->set(compact('data'));
                return;
            }

            $data[] = $entityAprho;

            // Salvar dados
            $this->connect->commit();

            $this->set(compact('data'));

        } catch (Exception $e) {
            //rollback da transacao
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    /**
     * APRHO
     * GET method
     */
    public function getAprho($codigo_qualificacao = null)
    {

        if (empty($codigo_qualificacao)) {
            $data = $this->Aprho->find()->select(
                ['codigo', 'codigo_qualificacao', 'exposicao_duracao', 'exposicao_frequencia', 'codigo_fonte_geradora_exposicao_tipo',
                    'codigo_fonte_geradora_exposicao', 'codigo_agente_exposicao', 'qualitativo', 'relevancia', 'aceitabilidade', 'conselho_tecnico_resultado',
                    'conselho_tecnico_resultado', 'conselho_tecnico_agenda', 'conselho_tecnico_descricao', 'codigo_conselho_tecnico_arquivo', 'medicoes_resultado', 'medicoes_agenda', 'arquivo_url']
            )->toArray();
        } else {
            $data = $this->Aprho->find()
                ->select(
                    ['codigo', 'codigo_qualificacao', 'exposicao_duracao', 'exposicao_frequencia', 'codigo_fonte_geradora_exposicao_tipo',
                        'codigo_fonte_geradora_exposicao', 'codigo_agente_exposicao', 'qualitativo', 'relevancia', 'aceitabilidade', 'conselho_tecnico_resultado',
                        'conselho_tecnico_resultado', 'conselho_tecnico_agenda', 'conselho_tecnico_descricao', 'codigo_conselho_tecnico_arquivo', 'medicoes_resultado', 'medicoes_agenda', 'arquivo_url']
                )
                ->where(['codigo_qualificacao' => $codigo_qualificacao]);
        }

        $this->set(compact('data'));
    }


    public function getAuthUser()
    {
        //pega os dados do token
        $dados_token = $this->getDadosToken();

        //veifica se encontrou os dados do token
        if (empty($dados_token)) {
            $error = 'Não foi possivel encontrar os dados no Token!';
            $this->set(compact('error'));
            return;
        }

        //seta o codigo usuario
        $codigo_usuario = (isset($dados_token->codigo_usuario)) ? $dados_token->codigo_usuario : '';

        if (empty($codigo_usuario)) {
            $error = 'Logar novamente o usuario';
            $this->set(compact('error'));
            return;
        }

        return $codigo_usuario;
    }
}
