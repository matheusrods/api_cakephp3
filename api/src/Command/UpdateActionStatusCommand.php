<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * UpdateActionStatus command.
 */
class UpdateActionStatusCommand extends Command
{
    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');

        $this->loadModel('AcoesMelhorias');
        $this->loadModel('AcoesMelhoriasSolicitacoes');
        $this->loadModel('PdaConfigRegra');
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->updateActionsForLate($io);
        $this->updateActionsOfStocksWithoutDeadline($io);
        $this->automaticallyAcceptResponsibilityImprovementAction($io);
    }

    /**
     * Atualizar ações de melhorias para o status de atrasado a partir do prazo e a data atual
     *
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return void
     */
    public function updateActionsForLate(ConsoleIo $io)
    {
        $io->out('Consultado ações que estão em atraso e alterando seu status...');

        $date = date('Y-m-d');

        try {
            // Consultar ações com status (1 = Aguardando analise, 2 = Analise pendente, 3 = Em andamento) e alterar para o status (4 = Atrasado)
            $actions = $this->AcoesMelhorias
                ->find()
                ->where([
                    'codigo_acoes_melhorias_status IN (1, 2, 3)',
                    "prazo < '$date'",
                ])
                ->all()
                ->toArray();

            $ids = [];

            foreach ($actions as $action) {
                array_push($ids, $action['codigo']);
            }

            $quantity = count($actions);

            if ($quantity === 0) {
                $io->success('Tarefa executada com sucesso. Nenhuma ação de melhoria foi afetada.');
            } else {
                $this->AcoesMelhorias
                    ->find()
                    ->whereInList('codigo', $ids)
                    ->update()
                    ->set([
                        'codigo_acoes_melhorias_status' => 4,
                    ])
                    ->execute();

                foreach ($ids as $id) {
                    try {
                        $this->PdaConfigRegra->getEmAcaoDeMelhoria((int) $id);
                    } catch (\Exception $exception) {
                        continue;
                    }
                }

                $io->success('Ações de melhorias alteradas com sucesso! Ações de melhorias afetadas: ' . $quantity . '.');
            }
        } catch (\Exception $th) {
            $io->error($th->getMessage());
        }
    }

    /**
     * Atualizar ações de melhorias para o status definido no portal, levando em conta o tempo definido também no portal e que a ação esteja com o prazo nulo
     *
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return void
     */
    public function updateActionsOfStocksWithoutDeadline(ConsoleIo $io)
    {
        $io->out('Consultado ações que estão dentro da regra de mudança de status (apenas ações de melhorias com prazo nulo)...');

        $fields = [
            'codigo' => 'AcoesMelhorias.codigo',
            'status_acao_sem_prazo' => 'RegraAcao.status_acao_sem_prazo',
        ];

        $conditions = [
            'AcoesMelhorias.codigo_acoes_melhorias_status IN (1, 2, 3, 4)',
            'AcoesMelhorias.data_remocao IS NULL',
            'AcoesMelhorias.prazo IS NULL',
            'DATEDIFF(DAY, GETDATE(), DATEADD(DAY, RegraAcao.dias_prazo, AcoesMelhorias.data_inclusao)) <= 0',
        ];

        $joins = [
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GruposEconomicosClientes',
                'type' => 'INNER',
                'conditions' => 'GruposEconomicosClientes.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao',
            ],
            [
                'table' => 'grupos_economicos',
                'alias' => 'GrupoEconomico',
                'type' => 'INNER',
                'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
            ],
            [
                'table' => 'regra_acao',
                'alias' => 'RegraAcao',
                'type' => 'INNER',
                'conditions' => 'RegraAcao.codigo_cliente = GrupoEconomico.codigo_cliente',
            ],
        ];

        $actions = $this->AcoesMelhorias
            ->find()
            ->select($fields)
            ->where($conditions)
            ->join($joins)
            ->all()
            ->toArray();

        $quantity = count($actions);

        if ($quantity === 0) {
            $io->success('Tarefa executada com sucesso. Nenhuma ação de melhoria foi afetada.');
        } else {
            $errors = 0;
            $success = 0;

            foreach ($actions as $action) {
                if (isset($action['status_acao_sem_prazo']) && !is_null($action['status_acao_sem_prazo'])) {
                    try {
                        $this->connect->begin();

                        $this->AcoesMelhorias
                            ->find()
                            ->where([
                                'codigo' => (int) $action['codigo'],
                            ])
                            ->update()
                            ->set([
                                'codigo_acoes_melhorias_status' => ((int) $action['status_acao_sem_prazo']),
                                'data_alteracao' => date('Y-m-d H:i:s'),
                            ])
                            ->execute();

                        $this->connect->commit();

                        $success += 1;
                    } catch (\Exception $th) {
                        $this->connect->rollback();

                        $errors += 1;

                        $io->error($th->getMessage());
                    }
                } else {
                    $errors += 1;
                }
            }

            $io->out('Ações de melhorias alteradas! Ações de melhorias afetadas: ' . $quantity . '. Com sucesso: ' . $success . '. Com erro: ' . $errors . '.');
        }
    }

    /**
     * Aceitar automaticamente responsabilidade da ação de melhoria
     *
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return void
     */
    public function automaticallyAcceptResponsibilityImprovementAction(ConsoleIo $io)
    {
        $io->out('Consultado solicitações de aceite em aberto (Status = 1)...');

        $fields = [
            'codigo' => 'AcoesMelhoriasSolicitacoes.codigo',
            'codigo_acao_melhoria' => 'AcoesMelhoriasSolicitacoes.codigo_acao_melhoria',
            'codigo_acoes_melhorias_status' => 'AcoesMelhorias.codigo_acoes_melhorias_status',
            'codigo_novo_usuario_responsavel' => 'AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel'
        ];

        $conditions = [
            'AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo' => 1,
            'AcoesMelhoriasSolicitacoes.status' => 1,
            'AcoesMelhoriasSolicitacoes.data_remocao IS NULL',
            'AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel IS NOT NULL',
            'DATEDIFF(DAY, GETDATE(), DATEADD(DAY, RegraAcao.dias_a_aceitar, AcoesMelhoriasSolicitacoes.data_inclusao)) <= 0',
        ];

        $joins = [
            [
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = (CASE WHEN UsuariosDados.cpf IS NULL THEN Usuario.apelido ELSE UsuariosDados.cpf END)',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo AND ClienteFuncionario.codigo_cliente = Usuario.codigo_cliente',
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetorCargo.codigo = (
                    SELECT TOP 1 _fsc.codigo FROM funcionario_setores_cargos _fsc
                        INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                    WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                        AND _fsc.data_fim IS NULL
                    ORDER BY _fsc.codigo desc
                )',
            ],
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GruposEconomicosClientes',
                'type' => 'INNER',
                'conditions' => 'GruposEconomicosClientes.codigo_cliente = FuncionarioSetorCargo.codigo_cliente_alocacao',
            ],
            [
                'table' => 'grupos_economicos',
                'alias' => 'GrupoEconomico',
                'type' => 'INNER',
                'conditions' => 'GrupoEconomico.codigo = GruposEconomicosClientes.codigo_grupo_economico',
            ],
            [
                'table' => 'regra_acao',
                'alias' => 'RegraAcao',
                'type' => 'INNER',
                'conditions' => 'RegraAcao.codigo_cliente = GrupoEconomico.codigo_cliente',
            ],
            [
                'table' => 'acoes_melhorias',
                'alias' => 'AcoesMelhorias',
                'type' => 'INNER',
                'conditions' => 'AcoesMelhorias.codigo = AcoesMelhoriasSolicitacoes.codigo_acao_melhoria AND AcoesMelhorias.data_remocao IS NULL',
            ],
        ];

        $requests = $this->AcoesMelhoriasSolicitacoes
            ->find()
            ->select($fields)
            ->where($conditions)
            ->join($joins)
            ->all()
            ->toArray();

        $quantity = count($requests);

        if ($quantity === 0) {
            $io->success('Tarefa executada com sucesso. Nenhuma solicitação foi afetada.');
        } else {
            $errors = 0;
            $success = 0;

            foreach ($requests as $request) {
                if (isset($request['codigo_novo_usuario_responsavel']) && !is_null($request['codigo_novo_usuario_responsavel'])) {
                    try {
                        $this->connect->begin();

                        $dateUpdated = date('Y-m-d H:i:s');

                        $this->AcoesMelhoriasSolicitacoes
                            ->find()
                            ->where([
                                'codigo' => (int) $request['codigo'],
                            ])
                            ->update()
                            ->set([
                                'status' => 2,
                                'data_alteracao' => $dateUpdated,
                                'codigo_usuario_alteracao' => null,
                                'alteracao_sistema' => 1,
                            ])
                            ->execute();

                        if (
                            ((int) $request['codigo_acoes_melhorias_status']) === 1
                            || ((int) $request['codigo_acoes_melhorias_status']) === 2
                        ) {
                            $this->AcoesMelhorias
                                ->find()
                                ->where([
                                    'codigo' => (int) $request['codigo_acao_melhoria'],
                                ])
                                ->update()
                                ->set([
                                    'codigo_usuario_responsavel' => ((int) $request['codigo_novo_usuario_responsavel']),
                                    'codigo_acoes_melhorias_status' => 3,
                                    'data_alteracao' => $dateUpdated,
                                ])
                                ->execute();
                        } else {
                            $this->AcoesMelhorias
                                ->find()
                                ->where([
                                    'codigo' => (int) $request['codigo_acao_melhoria'],
                                ])
                                ->update()
                                ->set([
                                    'codigo_usuario_responsavel' => ((int) $request['codigo_novo_usuario_responsavel']),
                                    'data_alteracao' => $dateUpdated,
                                ])
                                ->execute();
                        }

                        $this->PdaConfigRegra->getEmAcaoDeMelhoria((int) $request['codigo_acao_melhoria']);

                        $this->connect->commit();

                        $success += 1;
                    } catch (\Exception $th) {
                        $this->connect->rollback();

                        $errors += 1;

                        $io->error($th->getMessage());
                    }
                } else {
                    $errors += 1;
                }
            }

            $io->out('Solicitações alteradas! Solicitações afetadas: ' . $quantity . '. Com sucesso: ' . $success . '. Com erro: ' . $errors . '.');
        }
    }
}
