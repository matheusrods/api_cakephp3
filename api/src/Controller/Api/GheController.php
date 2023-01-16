<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use DateTime;

/**
 * Ghe Controller.
 *
 * @property \App\Model\Table\GheTable $Ghe
 *
 * @method \App\Model\Entity\Ghe[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GheController extends ApiController
{
    public $connect;

    public function initialize()
    {
        parent::initialize();
        $this->connect = ConnectionManager::get('default');

        $this->loadModel("Ghe");
        $this->loadModel("ArrtpariGhe");
        $this->loadModel("ArrtpaRi");
        $this->loadModel("CscGhe");
        $this->loadModel("ClientesSetoresCargos");
    }

    public function index($codigo_cliente = null)
    {
        $data = $this->Ghe->getAll($codigo_cliente);
        $this->set(compact('data'));
    }

    public function view($id = null)
    {
        $data = $this->Ghe->getById($id);

        if (empty($data)) {
            $error = "Código $id de GHE inexistente";
            $this->set(compact('error'));
            return;
        }

        $this->set(compact('data'));
    }

    public function add()
    {
        $this->request->allowMethod(['post']);

        $this->connect->begin();

        try {
            $data = array();

            $request = $this->request->getData();
            $codigo_usuario = $this->getAuthUser();

            $dados = array();
            $dados['chave_ghe']                     = $request['chave_ghe'];
            $dados['aprho_parecer_tecnico']         = $request['aprho_parecer_tecnico'];
            $dados['codigo_cliente']                = $request['codigo_cliente'];
            $dados['ghe_status']                    = $this->Ghe::AVALIAR_GHE;
            $dados['codigo_gerente_operacoes']      = $request['codigo_gerente_operacoes'];
            $dados['codigo_ehs_tecnico']            = $request['codigo_ehs_tecnico'];
            $dados['codigo_operador']               = $request['codigo_operador'];
            $dados['aprovacao_gerente_operacoes']   = $request['aprovacao_gerente_operacoes'];
            $dados['aprovacao_ehs_tecnico']         = $request['aprovacao_ehs_tecnico'];
            $dados['descricao_divergencia']         = $request['descricao_divergencia'];
            $dados['codigo_usuario_inclusao']       = $codigo_usuario;

            $entityGhe = $this->Ghe->newEntity($dados);

            // validação do enum aprho_parecer_tecnico
            if (
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ABAIXO_DA_TOLERANCIA &&
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ACIMA_DO_NIVEL_DE_ACAO &&
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ACIMA_DA_TOLERANCIA
            ) {
                $data['error'] = "Aprho parecer técnico {$dados['aprho_parecer_tecnico']} inválido";
                $this->set(compact('data'));
                return;
            }

            if (!$this->Ghe->save($entityGhe)) {
                $data['message'] = 'Erro ao inserir em GHE';
                $data['error'] = $entityGhe->errors();
                $this->set(compact('data'));
                return;
            }

            if (!empty($request['riscos_impactos'])) {
                foreach ($request['riscos_impactos'] as $risco_impacto) {
                    $ri = array();

                    // Verifica se existe o risco impacto
                    $existeRiscoImpacto = $this->ArrtpaRi->find()->where(['codigo' => $risco_impacto['codigo_arrtpa_ri']])->first();
                    if (empty($existeRiscoImpacto)) {
                        $data['error'] = "Código {$risco_impacto['codigo_arrtpa_ri']} de Riscos impactos inexistente";
                        $this->set(compact('data'));
                        return;
                    }

                    $ri['codigo_usuario_inclusao'] = $codigo_usuario;
                    $ri['codigo_ghe'] = $entityGhe['codigo'];
                    $ri['codigo_arrtpa_ri'] = $risco_impacto['codigo_arrtpa_ri'];

                    $entityArrtpariGhe = $this->ArrtpariGhe->newEntity($ri);

                    if (!$this->ArrtpariGhe->save($entityArrtpariGhe)) {
                        $data['message'] = 'Erro ao inserir Riscos impactos em GHE';
                        $data['error'] = $entityArrtpariGhe->errors();
                        $this->set(compact('data'));
                        return;
                    }
                }
            }

            if (!empty($request['setores'])) {
                foreach ($request['setores'] as $setor) {
                    if (!empty($setor['cargos'])) {
                        foreach ($setor['cargos'] as $cargo) {
                            $csc = array();

                            // verifica se existe o cargo
                            $existeClienteSetorCargo = $this->ClientesSetoresCargos->find()->where([
                                'codigo_cliente' => $request['codigo_cliente'],
                                'codigo_setor' => $setor['codigo_setor'],
                                'codigo_cargo' => $cargo['codigo_cargo']
                            ])->first();

                            if (empty($existeClienteSetorCargo)) {
                                $data['error'] = "Código {$cargo['codigo_cargo']} do Cargo inexistente";
                                $this->set(compact('data'));
                                return;
                            }

                            $csc['codigo_usuario_inclusao'] = $codigo_usuario;
                            $csc['codigo_ghe'] = $entityGhe['codigo'];
                            $csc['codigo_clientes_setores_cargos'] = $existeClienteSetorCargo['codigo'];

                            $entityCscGhe = $this->CscGhe->newEntity($csc);

                            if (!$this->CscGhe->save($entityCscGhe)) {
                                $data['message'] = 'Erro ao inserir o Cargo em GHE';
                                $data['error'] = $entityCscGhe->errors();
                                $this->set(compact('data'));
                                return;
                            }
                        }
                    }
                }
            }

            $data = $this->Ghe->getById($entityGhe['codigo']);

            $this->connect->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function edit()
    {
        $this->request->allowMethod(['put']);

        $this->connect->begin();

        try {
            $data = array();

            $request = $this->request->getData();
            $codigo_usuario = $this->getAuthUser();

            $ghe = $this->Ghe->find()->where(['codigo' => $request['codigo_ghe'], 'ativo' => 1])->first();

            if (empty($ghe)) {
                $error = "Código {$request['codigo_ghe']} de GHE inexistente";
                $this->set(compact('error'));
                return;
            }

            $dados = array();
            $dados['codigo_cliente']                = $request['codigo_cliente'];
            $dados['chave_ghe']                     = $request['chave_ghe'];
            $dados['aprho_parecer_tecnico']         = $request['aprho_parecer_tecnico'];
            $dados['ghe_status']                    = $request['ghe_status'];
            $dados['codigo_gerente_operacoes']      = $request['codigo_gerente_operacoes'];
            $dados['codigo_ehs_tecnico']            = $request['codigo_ehs_tecnico'];
            $dados['codigo_operador']               = $request['codigo_operador'];
            $dados['aprovacao_gerente_operacoes']   = $request['aprovacao_gerente_operacoes'];
            $dados['aprovacao_ehs_tecnico']         = $request['aprovacao_ehs_tecnico'];
            $dados['descricao_divergencia']         = $request['descricao_divergencia'];
            $dados['codigo_usuario_alteracao']      = $codigo_usuario;
            $dados['data_alteracao']                = date('Y-m-d H:i:s');

            $data_divergencia                       = new DateTime("{$request['data_divergencia']}");
            $dados['data_divergencia']              = $data_divergencia->format('Y-m-d H:i:s');
            $dados['divergencia_apontada_por']      = $request['divergencia_apontada_por'];;

            // validação no enum aprho_parecer_tecnico
            if (
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ABAIXO_DA_TOLERANCIA &&
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ACIMA_DO_NIVEL_DE_ACAO &&
                $dados['aprho_parecer_tecnico'] != $this->Ghe::AGENTE_ACIMA_DA_TOLERANCIA
            ) {
                $data['error'] = "Aprho parecer técnico {$dados['aprho_parecer_tecnico']} inválido";
                $this->set(compact('data'));
                return;
            }

            // validação no enum ghe_status
            if (
                $dados['ghe_status'] != $this->Ghe::GHE_APROVADA &&
                $dados['ghe_status'] != $this->Ghe::DIVERGENCIA_APONTADA &&
                $dados['ghe_status'] != $this->Ghe::AVALIAR_GHE
            ) {
                $data['error'] = "Status do GHE {$dados['ghe_status']} inválido";
                $this->set(compact('data'));
                return;
            }

            $entityGhe = $this->Ghe->patchEntity($ghe, $dados);

            if (!$this->Ghe->save($entityGhe)) {
                $data['message'] = 'Erro ao alterar GHE';
                $data['error'] = $entityGhe->errors();
                $this->set(compact('data'));
                return;
            }

            //Remove da tabela arrtpari_ghe
            $total = $this->ArrtpariGhe->deleteAll(['codigo_ghe' => $request['codigo_ghe']], false);

            if (!empty($request['riscos_impactos'])) {
                foreach ($request['riscos_impactos'] as $risco_impacto) {
                    $ri = array();

                    // verifica se existe o risco impacto
                    $existeRiscoImpacto = $this->ArrtpaRi->find()->where(['codigo' => $risco_impacto['codigo_arrtpa_ri']])->first();
                    if (empty($existeRiscoImpacto)) {
                        $data['error'] = "Código {$risco_impacto['codigo_arrtpa_ri']} de Riscos impactos inexistente";
                        $this->set(compact('data'));
                        return;
                    }

                    $ri['codigo_usuario_inclusao'] = $codigo_usuario;
                    $ri['codigo_ghe'] = $entityGhe['codigo'];
                    $ri['codigo_arrtpa_ri'] = $risco_impacto['codigo_arrtpa_ri'];

                    $entityArrtpariGhe = $this->ArrtpariGhe->newEntity($ri);

                    if (!$this->ArrtpariGhe->save($entityArrtpariGhe)) {
                        $data['message'] = 'Erro ao inserir Riscos impactos em GHE';
                        $data['error'] = $entityArrtpariGhe->errors();
                        $this->set(compact('data'));
                        return;
                    }
                }
            }

            //Remove da tabela csc_ghe
            $this->CscGhe->deleteAll(['codigo_ghe' => $request['codigo_ghe']], false);
            if (!empty($request['setores'])) {
                foreach ($request['setores'] as $setor) {
                    if (!empty($setor['cargos'])) {
                        foreach ($setor['cargos'] as $cargo) {
                            $csc = array();

                            // verifica se existe o cargo
                            $existeClienteSetorCargo = $this->ClientesSetoresCargos->find()->where([
                                'codigo_cliente' => $request['codigo_cliente'],
                                'codigo_setor' => $setor['codigo_setor'],
                                'codigo_cargo' => $cargo['codigo_cargo']
                            ])->first();

                            if (empty($existeClienteSetorCargo)) {
                                $data['error'] = "Código {$cargo['codigo_cargo']} do Cargo inexistente";
                                $this->set(compact('data'));
                                return;
                            }

                            $csc['codigo_usuario_inclusao'] = $codigo_usuario;
                            $csc['codigo_ghe'] = $entityGhe['codigo'];
                            $csc['codigo_clientes_setores_cargos'] = $existeClienteSetorCargo['codigo'];

                            $entityCscGhe = $this->CscGhe->newEntity($csc);

                            if (!$this->CscGhe->save($entityCscGhe)) {
                                $data['message'] = 'Erro ao inserir o Cargo em GHE';
                                $data['error'] = $entityCscGhe->errors();
                                $this->set(compact('data'));
                                return;
                            }
                        }
                    }
                }
            }

            $data = $this->Ghe->getById($entityGhe['codigo']);

            $this->connect->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            $this->connect->rollback();

            $error[] = $e->getMessage();
            $this->set(compact('error'));
            return;
        }
    }

    public function delete($codigo)
    {
        $ghe = $this->Ghe->find()->where(['codigo' => $codigo, 'ativo' => 1])->first();

        if (empty($ghe)) {
            $error = 'GHE não encontrado';
            $this->set(compact('error'));
            return;
        }

        $dados = array();

        $dados['codigo_usuario_alteracao']  = $this->getAuthUser();
        $dados['data_alteracao']            = date('Y-m-d H:i:s');
        $dados['ativo']                     = 0;

        $entityGhe = $this->Ghe->patchEntity($ghe, $dados);

        if (!$this->Ghe->save($entityGhe)) {
            $data['message'] = 'Erro ao deletar GHE';
            $data['error'] = $entityGhe->getErrors();
            $this->set(compact('data'));
            return;
        }

        // if (!$this->Ghe->delete($ghe)) {
        //     $error = 'Erro ao remover GHE';
        //     $this->set(compact('error'));
        //     return;
        // }

        $data['message'] = "GHE removido com sucesso";

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
