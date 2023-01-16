<?php

namespace App\Model\Table;

use App\Model\Table\PosTable as Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Utils\Comum;
use Cake\Log\Log;
use Cake\Collection\Collection;
use Cake\Event\Event;
use Cake\I18n\Time;

use Exception;

/**
 * Observador EHS - PosObs
 *
 * Operações relativas ao aplicativo Observador EHS
 */
class PosObsTable extends Table
{
    private $_connect = null;

    private $codigo_usuario_autenticado = null;

    private $codigo_empresa = null;

    const UTF8_DECODE_COLUMNS = [
        'criticidade_descricao',
        'local_observacao',
        'categoria_descricao',
        'localidades_codigo_local_empresa_cidade',
        'descricao_risco_tipo',
        'descricao_impacto',
        'descricao_aspecto',
        'endereco_completo_localidade',
    ];

    /**
     * Observador EHS - Verifica se o usuário é responsável pela área
     *
     * @return bool
     */
    public function usuarioSerResponsavel(): bool
    {
        /**@var UsuariosResponsaveisTable */
        $UsuariosResponsaveisTable = TableRegistry::getTableLocator()->get('UsuariosResponsaveis');

        $codigoUsuario      = $this->obterCodigoUsuarioAutenticado();
        $codigoCliente      = $this->obterCodigoMatrizPeloCodigoUsuario($codigoUsuario);
        $usuarioResponsavel = $UsuariosResponsaveisTable->find()
            ->where([
                'codigo_usuario' => $codigoUsuario,
                'codigo_cliente' => $codigoCliente
            ])
            ->first();

        return !empty($usuarioResponsavel);;
    }

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->_connect = ConnectionManager::get('default');

        $this->codigo_usuario_autenticado = $this->obterCodigoUsuarioAutenticado();

        $this->codigo_empresa = 1;
    }

    /**
     * Observador EHS - Obter registros de categorias(Tipos de Observação)
     *
     * @param array $conditions
     * @param array $fields
     *
     * @return ORM/Query|Exception
     */
    public function obterListaObservacaoTipos(array $conditions = [], array $fields = [])
    {
        try {
            $PosCategoriaTable = TableRegistry::getTableLocator()->get('PosCategorias');

            $conditions['codigo_pos_ferramenta'] = self::$CODIGO_POS_SISTEMA_OBSERVADOR;

            $data = $PosCategoriaTable->find()
                ->select(['codigo', 'descricao'])
                ->where($conditions)
                ->all()
                ->toArray();

            $tipos = array_map(function ($tipo) {
                return [
                    'codigo'    => $tipo['codigo'],
                    'descricao' => Comum::converterEncodingPara($tipo['descricao'])
                ];
            }, $data);

            return $tipos;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Observador EHS - Obter lista de observações
     *
     * @param array $filtros
     *
     * @return ORM/Entity|Exception
     */
    public function obterListaObservacoes(
        array $conditions = [],
        array $fields = [],
        array $joins = [],
        array $group = [],
        array $order = []
    ) {
        /**@var PosObsObservacao */
        $this->PosObsObservacaoTable    = TableRegistry::getTableLocator()->get('PosObsObservacao');
        /**@var PosObsParticipantesTable */
        $this->PosObsParticipantesTable = TableRegistry::getTableLocator()->get('PosObsParticipantes');
        /**@var PosObsAnexosTable */
        $this->PosObsAnexosTable        = TableRegistry::getTableLocator()->get('PosObsAnexos');
        /**@var PosObsRiscosTable */
        $this->PosObsRiscosTable        = TableRegistry::getTableLocator()->get('PosObsRiscos');

        $limit = $conditions['filtrar']['limit'];

        if (isset($conditions['codigo_unidade'])) {
            $conditions['PosObsObservacao.codigo_cliente'] =  $this->obterCodigoMatrizPeloCodigoFilial($conditions['codigo_unidade']);
            unset($conditions['codigo_unidade']);
        }

        if (isset($conditions['status'])) {
            $conditions['AcoesMelhoriasStatus.codigo'] = $conditions['status'];
            unset($conditions['status']);
        } else {
            if (isset($conditions['status_in'])) {
                $conditions['AcoesMelhoriasStatus.codigo IN'] = $conditions['status_in'];
                unset($conditions['status_in']);
            } else {
                $conditions['AcoesMelhoriasStatus.codigo'] = 1;
            }
        }

        if (isset($conditions['codigo_observacao'])) {
            $conditions['PosObsObservacao.codigo'] = $conditions['codigo_observacao'];
            unset($conditions['codigo_observacao']);
        }

        if (isset($conditions['filtrar'])) {
            // ['autor'=>$autor, 'periodo_de'=>$de, 'periodo_ate'=>$ate];
            if (isset($conditions['filtrar']['autor'])) {

                if ($conditions['filtrar']['autor'] == 'usuario') {
                    $conditions['PosObsObservacao.codigo_usuario_inclusao'] = $this->codigo_usuario_autenticado;
                }

                if ($conditions['filtrar']['autor'] == 'area') {
                    $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

                    if (!empty($setorECargo->codigo_setor)) {
                        $conditions['FuncionarioSetorCargo.codigo_setor'] = $setorECargo->codigo_setor;
                    }
                }
            }

            if (isset($conditions['filtrar']['periodo_de']) && isset($conditions['filtrar']['periodo_ate'])) {
                $data_inicial =  \DateTime::createFromFormat('Y-m-d H:i:s', $conditions['filtrar']['periodo_de'] . '00:00:00');
                $data_final =  \DateTime::createFromFormat('Y-m-d H:i:s', $conditions['filtrar']['periodo_ate'] . '23:59:59');

                if (!$data_inicial || !$data_final) {
                    throw new Exception("Erro na escolha da data");
                }

                array_push($conditions, "PosObsObservacao.data_observacao between '" . $data_inicial->format('Y-m-d H:i:s') . "' AND '" . $data_final->format('Y-m-d H:i:s') . "'");
            }

            unset($conditions['filtrar']);
        }

        $fields = [
            'codigo_observacao'                                 => 'PosObsObservacao.codigo',
            'data_observacao'                                   => 'PosObsObservacao.data_observacao',
            'status'                                            => 'AcoesMelhoriasStatus.codigo',
            'status_cor'                                        => 'AcoesMelhoriasStatus.cor',
            'status_descricao'                                  => 'RHHealth.dbo.ufn_decode_utf8_string(AcoesMelhoriasStatus.descricao)',
            'criticidade'                                       => 'PosCriticidade.codigo',
            'criticidade_cor'                                   => 'PosCriticidade.cor',
            'criticidade_descricao'                             => 'RHHealth.dbo.ufn_decode_utf8_string(PosCriticidade.descricao)',
            'codigo_local_observacao'                           => 'PosObsLocal.codigo',
            'local_observacao'                                  => 'RHHealth.dbo.ufn_decode_utf8_string(PosObsLocal.descricao)',
            'codigo_pos_categoria'                              => 'PosCategorias.codigo',
            'categoria_descricao'                               => 'RHHealth.dbo.ufn_decode_utf8_string(PosCategorias.descricao)',
            'codigo_status'                                     => 'PosObsObservacao.codigo_status',
            'usuario_responsavel_codigo'                        => 'UsuarioResponsavel.codigo',
            'codigo_cliente'                                    => 'PosObsObservacao.codigo',
            'localidades_codigo_local_empresa'                  => 'Endereco.codigo',
            'localidades_codigo_local_empresa_logradouro'       => 'Endereco.logradouro',
            'localidades_codigo_local_empresa_numero'           => 'Endereco.numero',
            'localidades_codigo_local_empresa_bairro'           => 'Endereco.bairro',
            'localidades_codigo_local_empresa_cidade'           => 'RHHealth.dbo.ufn_decode_utf8_string(Endereco.cidade)',
            'localidades_codigo_local_empresa_bairro'           => 'Endereco.bairro',
            'localidades_codigo_local_empresa_estado_descricao' => 'Endereco.estado_descricao',
            'localidades_codigo_localidade'                     => 'PosObsLocais.codigo_localidade',
            'localidades_codigo_bu'                             => 'PosObsLocais.codigo_cliente_bu',
            'localidades_codigo_opco'                           => 'PosObsLocais.codigo_cliente_opco',
            'observacao_data'                                   => 'PosObsObservacao.data_observacao',
            'observacao_hora'                                   => 'PosObsObservacao.data_observacao',
            'descricao_usuario_observou'                        => 'PosObsObservacao.descricao_usuario_observou',
            'descricao_usuario_acao'                            => 'PosObsObservacao.descricao_usuario_acao',
            'descricao_usuario_sugestao'                        => 'PosObsObservacao.descricao_usuario_sugestao',
            'descricao'                                         => 'PosObsObservacao.descricao',
            'descricao_codigo_local'                            => 'PosObsObservacao.codigo_local_descricao',
            'observacao_criticidade'                            => 'PosObsObservacao.observacao_criticidade',
            'qualidade_avaliacao'                               => 'PosObsObservacao.qualidade_avaliacao',
            'qualidade_descricao_complemento'                   => 'PosObsObservacao.qualidade_descricao_complemento',
            'qualidade_descricao_participantes_tratativa'       => 'PosObsObservacao.qualidade_descricao_participantes_tratativa',
            'status'                                            => 'AcoesMelhoriasStatusResponsavel.codigo',
            'status_cor'                                        => 'AcoesMelhoriasStatusResponsavel.cor',
            'status_descricao'                                  => 'AcoesMelhoriasStatusResponsavel.descricao',
        ];

        $joins = [
            [
                'table'      => 'pos_obs_locais',
                'alias'      => 'PosObsLocais',
                'type'       => 'INNER',
                'conditions' => 'PosObsLocais.codigo_pos_obs_observacao = PosObsObservacao.codigo',
            ],
            [
                'table'      => 'acoes_melhorias_status',
                'alias'      => 'AcoesMelhoriasStatus',
                'type'       => 'INNER',
                'conditions' => 'AcoesMelhoriasStatus.codigo = PosObsObservacao.codigo_status',
            ],
            [
                'table'      => 'acoes_melhorias_status',
                'alias'      => 'AcoesMelhoriasStatusResponsavel',
                'type'       => 'LEFT',
                'conditions' => 'AcoesMelhoriasStatusResponsavel.codigo = PosObsObservacao.codigo_status_responsavel ',
            ],
            [
                'table'      => 'pos_categorias',
                'alias'      => 'PosCategorias',
                'type'       => 'LEFT',
                'conditions' => 'PosCategorias.codigo = PosObsObservacao.codigo_pos_categoria',
            ],
            [
                'table'      => 'pos_criticidade',
                'alias'      => 'PosCriticidade',
                'type'       => 'LEFT',
                'conditions' => 'PosCriticidade.codigo = PosObsObservacao.observacao_criticidade',
            ],
            [
                'table'      => 'pos_obs_local',
                'alias'      => 'PosObsLocal',
                'type'       => 'LEFT',
                'conditions' => 'PosObsObservacao.codigo_pos_obs_local = PosObsLocal.codigo',
            ],
            [
                'table'      => 'usuario',
                'alias'      => 'UsuarioResponsavel',
                'type'       => 'LEFT',
                'conditions' => 'PosObsObservacao.codigo_usuario_status = UsuarioResponsavel.codigo',
            ],
            [
                'table'      => 'cliente',
                'alias'      => 'Cliente',
                'type'       => 'LEFT',
                'conditions' => 'PosObsObservacao.codigo_cliente = Cliente.codigo',
            ],
            [
                'table'      => 'cliente_endereco',
                'alias'      => 'Endereco',
                'type'       => 'LEFT',
                'conditions' => 'Cliente.codigo = Endereco.codigo_cliente',
            ],
        ];

        if (array_key_exists('FuncionarioSetorCargo.codigo_setor', $conditions)) {

            $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

            array_push($joins, [
                'table'      => 'funcionario_setores_cargos',
                'alias'      => 'FuncionarioSetoresCargos',
                'type'       => 'LEFT',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente = '
                    . $setorECargo->codigo_cliente
                    . ' AND FuncionarioSetoresCargos.codigo_setor = '
                    . $setorECargo->codigo_setor
                    . ' AND FuncionarioSetoresCargos.data_fim is NULL',
            ]);
        }

        try {

            $result = $this->PosObsObservacaoTable->find()
                ->select($fields)
                ->where($conditions)
                ->join($joins)
                ->group($group)
                ->order($order ?: ['PosObsObservacao.codigo DESC'])
                ->toArray();
            // ->sql();

            // debug($result);exit;

            if ($result) {

                foreach ($result as $key => $dados_obs) {

                    $riscos = $this->PosObsRiscosTable->obterPorCodigoObservacao($dados_obs['codigo_observacao']);
                    $participantes = $this->PosObsParticipantesTable->obterPorCodigoObservacao($dados_obs['codigo_observacao']);
                    $anexos = $this->PosObsAnexosTable->obterPorCodigoObservacao($dados_obs['codigo_observacao']);
                    $acoesMelhorias = $this->obterAcoesMelhoriasPeloCodigoObservacao($dados_obs['codigo_observacao']);

                    $result[$key]['riscos']         = $riscos;
                    $result[$key]['participantes']  = $participantes;
                    $result[$key]['anexos']         = $anexos;
                    $result[$key]['acoesMelhorias'] = $acoesMelhorias;
                }

                $transforme = $this->responseTransformObservacao($result);

                $result = $transforme;
            } else {
                $result = [];
            }

            return $result;
        } catch (Exception $e) {

            throw $e;
        }
    }

    /**
     * Observador EHS - Cancelar uma observação
     *
     * @param int $codigo_pos_observacao
     * @param array $formData
     *
     * @return ORM/Entity|Exception
     */
    public function cancelarObservacao(int $codigo_pos_observacao = null, array $formData = [])
    {
        $PosObsObservacao = TableRegistry::getTableLocator()->get('PosObsObservacao');

        // verifica se observacao existe e esta ativa
        $observacaoData = $PosObsObservacao->find()->where([
            'codigo'        => $codigo_pos_observacao,
            'codigo_status' => 1,
            'ativo'         => 1
        ])->first();

        if (empty($observacaoData)) {
            // se a consulta retornou vazia
            throw new Exception('Não foi encontrado dados referente ao código informado.', 1);
        }

        $justificativa = $formData['justificativa'];

        try {
            $payloadData = [
                'data_status'               => date('Y-m-d H:i:s'),
                'status'                    => 0,
                'ativo'                     => 0,
                'descricao_status'          => $justificativa,
                'codigo_status'             => 6,
                'codigo_status_responsavel' => 6,
            ];

            $obsEntity = $PosObsObservacao->patchEntity($observacaoData, $payloadData);

            if ($obsEntity->hasErrors()) {
                Log::debug($obsEntity->getErrors());
                throw new Exception("Não foi possível cancelar a Observação", 1);
            }

            if (!$PosObsObservacao->save($obsEntity)) {
                throw new Exception("Não foi possível cancelar a Observação", 1);
            }

            return true;
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    /**
     * Observador EHS - Obter uma observação
     *
     * @param int $codigo_pos_observacao
     *
     * @return ORM/Entity|Exception
     */
    public function obterObservacao(int $codigo_pos_observacao = null)
    {
        try {
            $conditions = [
                'codigo_observacao' => $codigo_pos_observacao,
                'status_in'         => [1, 2, 3, 4, 5, 6]
            ];

            return $this->obterListaObservacoes($conditions);
        } catch (Exception $e) {

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    /**
     * Observador EHS - Salvar uma Observação
     *
     * PROCESSO DE GRAVAÇÃO
     *
     * 1) Observação
     * 2) Observadores/Participantes
     * 3) Localidades
     * 4) Anexos
     * 5) Riscos e Impactos
     *
     * @param array $formData
     *
     * @return ORM/Entity|Exception
     */
    public function salvarObservacao($formData)
    {
        Log::debug(__METHOD__);

        // Sistemas
        $PosAnexos = TableRegistry::getTableLocator()->get('PosAnexos');

        // Observação
        $PosObsObservacao    = TableRegistry::getTableLocator()->get('PosObsObservacao');
        $PosCategorias       = TableRegistry::getTableLocator()->get('PosCategorias');
        $PosObsParticipantes = TableRegistry::getTableLocator()->get('PosObsParticipantes');
        $PosObsLocais        = TableRegistry::getTableLocator()->get('PosObsLocais');
        $PosObsAnexos        = TableRegistry::getTableLocator()->get('PosObsAnexos');
        $PosObsRiscos        = TableRegistry::getTableLocator()->get('PosObsRiscos');
        $PosConfiguracoes    = TableRegistry::getTableLocator()->get('PosConfiguracoes');

        $this->_connect->begin();

        try {

            $codigo_unidade = $formData['codigo_unidade'];
            $codigo_cliente = $this->obterCodigoMatrizPeloCodigoFilial($codigo_unidade);

            if (empty($codigo_cliente)) {
                throw new Exception("Não foi possível gravar a Observação", 1);
            }

            Log::debug("codigo_unidade: {$codigo_unidade}  codigo_cliente: {$codigo_cliente}");

            // valida o codigo_cliente fornecido com os códigos de alocação disponíveis para o usuário autenticado
            if (!$this->validaCodigoClienteUsuarioAutenticado($codigo_cliente)) {
                throw new Exception("Não foi encontrado codigo_cliente: {$codigo_cliente} relacionado à este usuário {$this->codigo_usuario_autenticado} ", 1);
            }

            // se fornecido o codigo_observacao então a observação será atualizada
            $codigo_observacao = isset($formData['codigo_observacao']) && !empty($formData['codigo_observacao']) ? $formData['codigo_observacao'] : null;

            $codigo_categoria_observacao = $formData['codigo_categoria_observacao'];

            $data_observacao = Comum::formataData($formData['observacao_data'] . ' ' . $formData['observacao_hora'] . ':00', 'dmyhms', 'timestamp');

            $dataObsDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $data_observacao);

            // Valida se data e hora são corretas para gravar uma observação
            $data_observacaoTime = new Time($dataObsDateTime);
            $data_atual          = new Time();
            $distancia           = $data_observacaoTime->diffInDays($data_atual);
            $quantidade_dias     = (int) $PosConfiguracoes
                ->buscarConfig(3, $codigo_cliente, 'TEMPOVISUALIZACAORETROATIVA')['valor'];

            if ($distancia > $quantidade_dias) {
                throw new Exception(
                    "A data informada possui mais de {$quantidade_dias} dias. São {$distancia} dias anteriores a data atual, por esse motivo não será possível registrar esta observação.",
                    1
                );
            }

            // verifica se categoria ainda existe para o payload recebido
            $categoriasData = $PosCategorias->find()->where([
                'codigo'         => $codigo_categoria_observacao,
                'codigo_cliente' => $codigo_cliente
            ])->first();

            if (empty($categoriasData) || empty($codigo_categoria_observacao)) {
                throw new Exception("Tipo de Observação não foi encontrada", 1);
            }

            // 1) Registrando Observacao

            $payloadData = [
                'codigo_empresa'             => $this->codigo_empresa,
                'codigo_cliente'             => $codigo_cliente,
                'codigo_unidade'             => $codigo_unidade,
                'codigo_pos_categoria'       => $codigo_categoria_observacao,
                'codigo_pos_obs_local'       => $formData['codigo_local'],
                'data_observacao'            => $dataObsDateTime->format('Y-m-d H:i:s'),
                'descricao_usuario_observou' => $formData['descricao_usuario_observou'],
                'descricao_usuario_acao'     => $formData['descricao_usuario_acao'],
                'descricao_usuario_sugestao' => $formData['descricao_usuario_sugestao'],
                'descricao'                  => $formData['descricao'],
                'codigo_local_descricao'     => $formData['descricao_codigo_local'],
                'data_status'                => date('Y-m-d H:i:s'),
                'codigo_status'              => 1,   // Aguardando análise
                'status'                     => 1,   // Aguardando análise
                'codigo_status_responsavel'  => 2,   // Análise pendente
                'codigo_usuario_status'      => $this->codigo_usuario_autenticado,
            ];

            $observacaoEntity  = $PosObsObservacao->salvar($codigo_observacao, $payloadData);
            $codigo_observacao = $observacaoEntity->codigo;

            // 2) Observadores/participantes

            $observadores = $formData['observadores'];

            $PosObsParticipantes->salvarPorCodigoObservacao($codigo_observacao, $observadores);


            // 3) Localidades

            $localidades = $formData['localidades'];

            $payloadData = [
                'codigo_pos_obs_observacao' => $codigo_observacao,
                'codigo_local_empresa'      => $localidades['codigo_local_empresa'],
                'codigo_localidade'         => $localidades['codigo_localidade'],
                'codigo_cliente_bu'         => $localidades['codigo_bu'],
                'codigo_cliente_opco'       => $localidades['codigo_opco']
            ];

            $PosObsLocais->salvarPorCodigoObservacao($codigo_observacao, $payloadData);

            // 4) Anexos
            $anexos            = $formData['anexos'];

            if (!empty($anexos)) {

                foreach ($anexos as $key => $anexo) {
                    // Salva anexo
                    $payloadData = [
                        'codigo_empresa' => $this->codigo_empresa,
                    ];

                    $anexoEntity = $PosAnexos->salvarAnexo($anexo['arquivo'], 'observador', self::$CODIGO_POS_SISTEMA_OBSERVADOR, $codigo_cliente, $payloadData);

                    $codigo_pos_anexo = $anexoEntity->codigo;

                    unset($anexoEntity);

                    // Salva relação com a observação
                    $payloadData = [
                        'codigo_pos_obs_observacao' => $codigo_observacao,
                        'codigo_pos_anexo'          => $codigo_pos_anexo,
                    ];

                    $obsAnexosEntity = $PosObsAnexos->newEntity($payloadData);
                    $obsAnexosEntity->set(['codigo_usuario_inclusao' => $this->codigo_usuario_autenticado]);

                    if ($obsAnexosEntity->hasErrors()) {
                        Log::debug($obsAnexosEntity->getErrors());
                        throw new Exception("Não foi possível gravar os Anexos desta Observação", 1);
                    }

                    if (!$PosObsAnexos->save($obsAnexosEntity)) {
                        throw new Exception("Não foi possível gravar os Anexos desta Observação", 1);
                    }
                }
            }

            // 5) riscos e impactos

            $riscos = $formData['riscos'];

            $PosObsRiscos->salvarPorCodigoObservacao($codigo_observacao, $riscos,);


            $this->_connect->commit();


            $eventoFeedbackObservador = new Event('Model.PosObsObservacao.feedbackObservador', $this, [
                'codigo' => (int) $codigo_observacao
            ]);

            $this->getEventManager()->dispatch($eventoFeedbackObservador);

            return true;
        } catch (Exception $e) {

            $this->_connect->rollback();

            Log::debug($e->getMessage());

            throw $e;
        }
    }

    /**
     * Observador EHS - Registra uma Classificação de Risco para uma Observação
     *
     * @param array $formData
     *
     * @return ORM/Entity|Exception
     */
    public function salvarClassificacaoRisco($formData)
    {
        /**@var PosObsObservacaoAcaoMelhoriaTable */
        $VinculoAcaoMelhoriaTable = TableRegistry::getTableLocator()
            ->get('PosObsObservacaoAcaoMelhoria');

        /**@var PosObsObservacaoTable */
        $ObservacaoTable = TableRegistry::getTableLocator()
            ->get('PosObsObservacao');

        /**@var AcoesMelhoriasTable */
        $AcoesMelhoriasTable = TableRegistry::getTableLocator()
            ->get('AcoesMelhorias');

        $acoesParaRelacionar = [];

        $this->_connect->begin();

        try {
            $observacao = $ObservacaoTable->find('all')
                ->where([
                    'codigo' => (int) $formData['codigo_observacao'],
                    'ativo'  => 1,
                    'status' => 1,
                ])
                ->firstOrFail();

            $codigo_cliente = $this->obterCodigoMatrizPeloCodigoFilial($observacao['codigo_unidade']);

            if (empty($codigo_cliente)) {
                throw new Exception("Não foi possível registrar uma Classificação, codigo_unidade não possui vínculo", 1);
            }

            $resultadoRegistroAcaoMelhoria = $AcoesMelhoriasTable->store(
                $formData['acoes_melhoria_registro'],
                $this->codigo_usuario_autenticado,
                false
            );

            if (isset($resultadoRegistroAcaoMelhoria['data']['error'])) {
                throw new Exception(
                    "Não foi possível registrar uma Ação de Melhoria. " . $resultadoRegistroAcaoMelhoria['data']['error']['message'],
                    1
                );
            }

            foreach ($resultadoRegistroAcaoMelhoria['data'] as $acao) {
                array_push($acoesParaRelacionar, $acao['codigo']);
            }

            foreach ($formData['acoes_melhoria_vinculo'] as $acaoVinculo) {
                $AcoesMelhoriasTable->find()->where([
                    'codigo' => (int) $acaoVinculo['codigo']
                ])->firstOrFail();

                array_push($acoesParaRelacionar, $acaoVinculo['codigo']);
            }

            foreach ($acoesParaRelacionar as $acaoMelhoriaCodigo) {
                $vinculoEntity = $VinculoAcaoMelhoriaTable->newEntity([
                    'codigo_usuario_inclusao' => $this->codigo_usuario_autenticado,
                    'data_inclusao'           => date('Y-m-d H:i:s'),
                    'acoes_melhoria_id'       => $acaoMelhoriaCodigo,
                    'obs_observacao_id'       => $observacao['codigo']
                ]);

                if (!$VinculoAcaoMelhoriaTable->save($vinculoEntity)) {
                    throw new Exception(
                        "Não foi possível vincular a Classificação de Risco",
                        1
                    );
                }
            }

            $dadosParaAtualizarObservacao = [
                'observacao_criticidade'                      => $formData['criticidade'],
                'qualidade_avaliacao'                         => $formData['avaliacao'],
                'qualidade_descricao_complemento'             => $formData['descricao_complemento'],
                'qualidade_descricao_participantes_tratativa' => $formData['descricao_participantes_tratativa'],
                'status'                                      => 2,
                'codigo_status'                               => 5,
                'codigo_status_responsavel'                   => 5,
            ];

            $observacaoEntity = $ObservacaoTable->patchEntity(
                $observacao,
                $dadosParaAtualizarObservacao
            );

            if (!$ObservacaoTable->save($observacaoEntity)) {
                throw new Exception(
                    " Não foi possível atualizar Observação e registrar a Classificação de Risco",
                    1
                );
            }

            $this->_connect->commit();

            /**
             * Implementação das regras
             * a partir de uma classificação
             */
            $eventoFeedbackObservador = new Event('Model.PosObsObservacao.feedbackObservador', $this, [
                'codigo' => (int) $formData['codigo_observacao']
            ]);

            $eventoCriticidade = new Event('Model.PosObsObservacao.notificarPelaCriticidade', $this, [
                'codigo' => (int) $formData['codigo_observacao']
            ]);

            $this->getEventManager()->dispatch($eventoFeedbackObservador);
            $this->getEventManager()->dispatch($eventoCriticidade);

            return true;
        } catch (Exception $e) {
            $this->_connect->rollback();

            throw $e;
        }
    }

    /**
     * Observador EHS - Retorna todas as ações de melhorias vinculadas a uma observação
     *
     * @param int $codigo
     *
     * @return array|Exception
     */
    public function obterAcoesMelhoriasPeloCodigoObservacao(int $codigo = null)
    {
        /**@var PosObsObservacaoTable */
        $ObservacaoTable = TableRegistry::getTableLocator()
            ->get('PosObsObservacao');

        $acoesMelhorias = $ObservacaoTable->find()
            ->where(['codigo' => (int) $codigo])
            ->contain([
                'AcoesMelhorias' => [
                    'queryBuilder' => function ($query) {
                        return $query->select([
                            'AcoesMelhorias.codigo',
                            'AcoesMelhorias.prazo',
                            'AcoesMelhorias.descricao_acao',
                            'AcoesMelhorias.descricao_desvio',
                            'AcoesMelhorias.descricao_local_acao',
                            'AcoesMelhorias.formulario_resposta',
                            'AcoesMelhorias.data_conclusao',
                            'AcoesMelhoriasStatus.codigo',
                            'AcoesMelhoriasStatus.cor',
                            'AcoesMelhoriasStatus.descricao',
                            'AcoesMelhoriasTipo.codigo',
                            'AcoesMelhoriasTipo.descricao',
                            'AcoesMelhorias.data_inclusao',
                            'PosCriticidade.codigo',
                            'PosCriticidade.descricao',
                            'PosCriticidade.cor',
                            'UsuarioResponsavel.codigo',
                            'UsuarioResponsavel.nome',
                            'OrigemFerramentas.codigo',
                            'OrigemFerramentas.codigo_cliente',
                            'OrigemFerramentas.descricao',
                        ])->contain([
                            'AcoesMelhoriasStatus',
                            'AcoesMelhoriasTipo',
                            'PosCriticidade',
                            'UsuarioResponsavel',
                            'UsuarioIdentificador' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'UsuarioIdentificador.codigo',
                                        'UsuarioIdentificador.nome',
                                        'UsuariosDados.codigo',
                                        'UsuariosDados.avatar',
                                    ])
                                        ->contain(['UsuariosDados']);
                                },
                            ],
                            'OrigemFerramentas',
                            'Cliente' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'Cliente.codigo',
                                        'Cliente.razao_social',
                                        'Cliente.nome_fantasia',
                                        'Endereco.codigo',
                                        'Endereco.cep',
                                        'Endereco.logradouro',
                                        'Endereco.numero',
                                        'Endereco.bairro',
                                        'Endereco.cidade',
                                        'Endereco.estado_descricao',
                                        'Endereco.complemento',
                                        'endereco_completo_localidade' => "RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao))",
                                    ])->contain(['Endereco']);
                                },
                            ],
                            'AcoesMelhoriasSolicitacoes' => [
                                'queryBuilder' => function ($query) {
                                    return $query->select([
                                        'codigo',
                                        'codigo_acao_melhoria',
                                        'codigo_acao_melhoria_solicitacao_tipo',
                                        'codigo_novo_usuario_responsavel',
                                        'codigo_usuario_solicitado',
                                        'status',
                                        'novo_prazo',
                                        'justificativa_solicitacao',
                                        'justificativa_recusa',
                                        'codigo_usuario_inclusao',
                                        'codigo_usuario_alteracao',
                                        'data_inclusao',
                                        'data_alteracao',
                                        'data_remocao',
                                        'nome_usuario_inclusao' => 'UsuarioInclusaoSolicitacao.nome',
                                        'nome_novo_usuario_responsavel' => 'NovoUsuarioResponsavel.nome',
                                        'nome_usuario_solicitado' => 'UsuarioSolicitado.nome',
                                    ])->join([
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioInclusaoSolicitacao',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'NovoUsuarioResponsavel',
                                            'type' => 'LEFT',
                                            'conditions' => 'NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel',
                                        ],
                                        [
                                            'table' => 'usuario',
                                            'alias' => 'UsuarioSolicitado',
                                            'type' => 'LEFT',
                                            'conditions' => 'UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado',
                                        ],
                                    ]);
                                },
                            ],
                        ]);
                    },
                ],
            ])
            ->firstOrFail()
            ->get('acoes_melhorias');

        foreach ($acoesMelhorias as $acao) {
            unset($acao['_joinData']);
        }

        return $acoesMelhorias;
    }

    private function utf8DecodeColumns($row)
    {

        foreach ($row as $columnName => $columnValue) {

            if (
                in_array($columnName, self::UTF8_DECODE_COLUMNS)
            ) {
                $columnValueBefore = $columnValue;
                $row[$columnName] = utf8_decode($columnValue);

                $jsonEncodeRow = json_encode($row);

                if (empty($jsonEncodeRow)) {
                    $row[$columnName] = $columnValueBefore;
                }
            }
        }

        return $row;
    }

    /**
     * Observador EHS - Transform do resultado de Observação
     *
     * @param Query|ResultSet $data
     * @return void
     */
    private function responseTransformObservacao($data)
    {
        foreach ($data as $key => $dados_obs) {

            $observacao = (new Time($dados_obs['observacao_data']));
            $observacao_data = $observacao->i18nFormat('dd/MM/yyyy');
            $observacao_hora = $observacao->i18nFormat('HH:mm');

            $local_empresa_descricao = null;

            if (!empty($dados_obs['localidades_codigo_local_empresa_logradouro'])) {

                $v = $dados_obs['localidades_codigo_local_empresa_logradouro'];

                if (!empty(Comum::soNumero($dados_obs['localidades_codigo_local_empresa_numero']))) {
                    $v = $v . ' ,' . Comum::soNumero($dados_obs['localidades_codigo_local_empresa_numero']);
                }

                if (!empty($dados_obs['localidades_codigo_local_empresa_bairro'])) {
                    $v = $v . ' - ' . $dados_obs['localidades_codigo_local_empresa_bairro'];
                }

                if (!empty($dados_obs['localidades_codigo_local_empresa_estado_descricao'])) {
                    $v = $v . ', ' . $dados_obs['localidades_codigo_local_empresa_estado_descricao'];
                }

                $local_empresa_descricao = $v;
            }

            $data[$key] = [
                'codigo_observacao'           => $dados_obs['codigo_observacao'],
                'codigo_unidade'              => empty($dados_obs['localidade']['codigo']) ? null : $dados_obs['localidade']['codigo'],
                'codigo_categoria_observacao' => $dados_obs['codigo_pos_categoria'],
                'codigo_local_observacao'     => $dados_obs['codigo_local_observacao'],
                'local_observacao'            => $dados_obs['local_observacao'],
                'categoria_descricao'         => $dados_obs['categoria_descricao'],
                'observadores'                => $dados_obs['participantes'],
                'localidades'                 => [
                    "codigo_local_empresa"    => $this->soNumero($dados_obs['localidades_codigo_local_empresa']),
                    "local_empresa_descricao" => $local_empresa_descricao,
                    "codigo_localidade"       => $this->soNumero($dados_obs['localidades_codigo_localidade']),
                    "codigo_bu"               => $this->soNumero($dados_obs['localidades_codigo_bu']),
                    "codigo_opco"             => $this->soNumero($dados_obs['localidades_codigo_opco'])
                ],
                'observacao_data'            => $observacao_data,
                'observacao_hora'            => $observacao_hora,
                'descricao_usuario_observou' => $dados_obs['descricao_usuario_observou'],
                'descricao_usuario_acao'     => $dados_obs['descricao_usuario_acao'],
                'descricao_usuario_sugestao' => $dados_obs['descricao_usuario_sugestao'],
                'descricao'                  => $dados_obs['descricao'],
                'status_codigo'              => $dados_obs['status'],
                'status_cor'                 => $dados_obs['status_cor'],
                'status_descricao'           => $dados_obs['status_descricao'],
                'criticidade_codigo'         => $dados_obs['criticidade'],
                'criticidade_cor'            => $dados_obs['criticidade_cor'],
                'criticidade_descricao'      => $dados_obs['criticidade_descricao'],
                'riscos'                     => $dados_obs['riscos'],
                'anexos'                     => $dados_obs['anexos'],
                'acoes_melhorias'            => $dados_obs['acoesMelhorias'],
            ];
        }

        return $data;
    }

    public function cteobterListaObservacoesTimer($conditions)
    {

        $this->PosObsObservacaoTable    = TableRegistry::getTableLocator()->get('PosObsObservacao');

        $where = $this->montaConditions($conditions);

        $joins = "";

        if (array_key_exists('FuncionarioSetorCargo.codigo_setor', $conditions)) {

            $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

            $joins = "LEFT JOIN funcionario_setores_cargos FuncionarioSetoresCargos ON FuncionarioSetoresCargos.codigo_cliente = '" . $setorECargo->codigo_cliente . "' AND FuncionarioSetoresCargos.codigo_setor = '" . $setorECargo->codigo_setor . "' AND FuncionarioSetoresCargos.data_fim is NULL";
        }

        $startTimeSql = microtime(true);

        $sql = "
            WITH CtePosObsObservacao AS (
                
                SELECT
                    --TOP 20 
                    PosObsObservacao.codigo AS [codigo_observacao],
                    PosObsObservacao.codigo_unidade as codigo_unidade,
                    PosObsObservacao.codigo_usuario_status,
                    PosObsObservacao.data_observacao AS [data_observacao],
                    AcoesMelhoriasStatusResponsavel.codigo AS [status],
                    AcoesMelhoriasStatusResponsavel.cor AS [status_cor],
                    AcoesMelhoriasStatusResponsavel.descricao AS [status_descricao],
                    PosCriticidade.codigo AS [criticidade],
                    PosCriticidade.cor AS [criticidade_cor],
                    RHHealth.dbo.ufn_decode_utf8_string(PosCriticidade.descricao) AS [criticidade_descricao],
                    PosObsLocal.codigo AS [codigo_local_observacao],
                    RHHealth.dbo.ufn_decode_utf8_string(PosObsLocal.descricao) AS [local_observacao],
                    PosCategorias.codigo AS [codigo_pos_categoria],
                    RHHealth.dbo.ufn_decode_utf8_string(PosCategorias.descricao) AS [categoria_descricao],
                    PosObsObservacao.codigo_status AS [codigo_status],
                    UsuarioResponsavel.codigo AS [usuario_responsavel_codigo],
                    PosObsObservacao.codigo AS [codigo_cliente],
                    Endereco.codigo AS [localidades_codigo_local_empresa],
                    Endereco.logradouro AS [localidades_codigo_local_empresa_logradouro],
                    Endereco.numero AS [localidades_codigo_local_empresa_numero],
                    Endereco.bairro AS [localidades_codigo_local_empresa_bairro],
                    RHHealth.dbo.ufn_decode_utf8_string(Endereco.cidade) AS [localidades_codigo_local_empresa_cidade],
                    Endereco.estado_descricao AS [localidades_codigo_local_empresa_estado_descricao],
                    PosObsLocais.codigo_localidade AS [localidades_codigo_localidade],
                    PosObsLocais.codigo_cliente_bu AS [localidades_codigo_bu],
                    PosObsLocais.codigo_cliente_opco AS [localidades_codigo_opco],
                    PosObsObservacao.data_observacao AS [observacao_data],
                    PosObsObservacao.data_observacao AS [observacao_hora],
                    PosObsObservacao.descricao_usuario_observou AS [descricao_usuario_observou],
                    PosObsObservacao.descricao_usuario_acao AS [descricao_usuario_acao],
                    PosObsObservacao.descricao_usuario_sugestao AS [descricao_usuario_sugestao],
                    PosObsObservacao.descricao AS [descricao],
                    PosObsObservacao.codigo_local_descricao AS [descricao_codigo_local],
                    PosObsObservacao.observacao_criticidade AS [observacao_criticidade],
                    PosObsObservacao.qualidade_avaliacao AS [qualidade_avaliacao],
                    PosObsObservacao.qualidade_descricao_complemento AS [qualidade_descricao_complemento],
                    PosObsObservacao.qualidade_descricao_participantes_tratativa AS [qualidade_descricao_participantes_tratativa]
                FROM
                    pos_obs_observacao PosObsObservacao
                    INNER JOIN pos_obs_locais PosObsLocais ON PosObsLocais.codigo_pos_obs_observacao = PosObsObservacao.codigo
                    INNER JOIN acoes_melhorias_status AcoesMelhoriasStatus ON AcoesMelhoriasStatus.codigo = PosObsObservacao.codigo_status
                    LEFT JOIN acoes_melhorias_status AcoesMelhoriasStatusResponsavel ON AcoesMelhoriasStatusResponsavel.codigo = PosObsObservacao.codigo_status_responsavel
                    LEFT JOIN pos_categorias PosCategorias ON PosCategorias.codigo = PosObsObservacao.codigo_pos_categoria
                    LEFT JOIN pos_criticidade PosCriticidade ON PosCriticidade.codigo = PosObsObservacao.observacao_criticidade
                    LEFT JOIN pos_obs_local PosObsLocal ON PosObsObservacao.codigo_pos_obs_local = PosObsLocal.codigo
                    LEFT JOIN usuario UsuarioResponsavel ON PosObsObservacao.codigo_usuario_status = UsuarioResponsavel.codigo
                    LEFT JOIN cliente Cliente ON PosObsObservacao.codigo_cliente = Cliente.codigo
                    LEFT JOIN cliente_endereco Endereco ON Cliente.codigo = Endereco.codigo_cliente
                    {$joins}
                WHERE 1=1
                {$where}                    
            ),

            CtePosObsRiscos AS (

                SELECT
                    CtePosObsObservacao.*,
                    PosObsRiscos.codigo AS [PosObsRiscos__codigo],
                    RiscosTipo.codigo AS [codigo_risco_tipo],
                    RiscosTipo.icone AS [risco_tipo_icone],
                    RiscosTipo.cor AS [risco_tipo_cor],
                    RHHealth.dbo.ufn_decode_utf8_string(RiscosTipo.descricao) AS [descricao_risco_tipo],
                    RiscosImpactos.codigo AS [codigo_impacto],
                    RHHealth.dbo.ufn_decode_utf8_string(RiscosImpactos.descricao) AS [descricao_impacto],
                    PerigosAspectos.codigo AS [codigo_aspecto],
                    RHHealth.dbo.ufn_decode_utf8_string(PerigosAspectos.descricao) AS [descricao_aspecto]
                FROM
                CtePosObsObservacao as CtePosObsObservacao
                    LEFT JOIN pos_obs_riscos PosObsRiscos ON PosObsRiscos.codigo_pos_obs_observacao = CtePosObsObservacao.codigo_observacao
                    LEFT JOIN riscos_tipo RiscosTipo ON PosObsRiscos.codigo_arrt = (RiscosTipo.codigo)
                    LEFT JOIN riscos_impactos RiscosImpactos ON PosObsRiscos.codigo_arrtpa_ri = (RiscosImpactos.codigo)
                    LEFT JOIN perigos_aspectos PerigosAspectos ON PosObsRiscos.codigo_arrt_pa = (PerigosAspectos.codigo)
                WHERE 1=1
            ),

            cteParticipantes AS (
                SELECT
                    CtePosObsRiscos.*,
                    PosObsParticipantes.codigo AS [codigo],
                    PosObsParticipantes.codigo_usuario AS [codigo_usuario],
                    UsuarioResponsavel.nome AS [nome],
                    UsuariosDados.avatar AS [avatar]
                FROM CtePosObsRiscos as CtePosObsRiscos 
                    LEFT JOIN pos_obs_participantes PosObsParticipantes ON PosObsParticipantes.codigo_pos_obs_observacao = CtePosObsRiscos.codigo_observacao
                    LEFT JOIN usuario UsuarioResponsavel ON PosObsParticipantes.codigo_usuario = (UsuarioResponsavel.codigo)
                    LEFT JOIN usuario UsuarioIdentificador ON PosObsParticipantes.codigo_usuario = (UsuarioIdentificador.codigo)
                    LEFT JOIN usuarios_dados UsuariosDados ON UsuarioIdentificador.codigo = (UsuariosDados.codigo_usuario)
                WHERE 1=1
            ),

            ctePosAnexos AS (

                SELECT
                    cteParticipantes.*,
                    PosObsAnexos.codigo AS [codigo_anexo],
                    PosAnexos.arquivo_url AS [arquivo]
                FROM cteParticipantes as cteParticipantes 
                    LEFT JOIN pos_obs_anexos PosObsAnexos on PosObsAnexos.codigo_pos_obs_observacao = cteParticipantes.codigo_observacao
                    LEFT JOIN pos_anexos PosAnexos ON (
                        PosAnexos.codigo_pos_ferramenta = 3
                        AND PosObsAnexos.codigo_pos_anexo = (PosAnexos.codigo)
                        AND PosObsAnexos.ativo = 1
                        AND PosAnexos.ativo = 1
                    )
                WHERE 1=1
            ),

            cteAcaoMelhoria AS (


            select
                ctePosAnexos.*,
                AcoesMelhorias.codigo as cte_codigo_acao_melhoria,
                AcoesMelhorias.prazo,
                AcoesMelhorias.descricao_acao,
                AcoesMelhorias.descricao_desvio,
                AcoesMelhorias.descricao_local_acao,
                AcoesMelhorias.formulario_resposta,
                AcoesMelhorias.data_conclusao,
                AcoesMelhoriasStatus.codigo as codigo_status_acao_melhoria,
                AcoesMelhoriasStatus.cor,
                AcoesMelhoriasStatus.descricao as descricao_melhorias_status,
                AcoesMelhoriasTipo.codigo as codigo_tipo_acao_melhoria,
                AcoesMelhoriasTipo.descricao as descricao_tipo,
                AcoesMelhorias.data_inclusao,
                PosCriticidade.codigo as codigo_pos_criticidade,
                PosCriticidade.descricao as descricao_pos_criticidade,
                PosCriticidade.cor as cor_pos_criticidade,
                UsuarioResponsavel.codigo as codigo_usuario_responsavel,
                UsuarioResponsavel.nome as nome_usuario_responsavel,
                OrigemFerramentas.codigo as codigo_origem_ferramenta,
                OrigemFerramentas.codigo_cliente as codigo_cliente_origem_ferramenta,
                OrigemFerramentas.descricao as desc_origem_ferramenta,
                UsuarioIdentificador.codigo as codigo_usuario_identificador,
                UsuarioIdentificador.nome as nome_usuario_identificador,
                UsuarioIdentificador.codigo_cliente as codigo_cliente_identificador,
                UsuariosDados.codigo as codigo_usuarios_dados,
                UsuariosDados.avatar as avatar_usuarios_dados,
                Cliente.codigo as codigo_cliente_acao_melhoria,
                Cliente.razao_social,
                Cliente.nome_fantasia,
                Endereco.codigo as codigo_endereco,
                Endereco.cep,
                Endereco.logradouro,
                Endereco.numero,
                Endereco.bairro,
                Endereco.cidade,
                Endereco.estado_descricao,
                Endereco.complemento,
                RHHealth.dbo.ufn_decode_utf8_string(CONCAT(Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao)) as endereco_completo_localidade,
                AcoesMelhoriasSolicitacoes.codigo as codigo_acoes_melhorias_solicitacoes,
                AcoesMelhoriasSolicitacoes.codigo_acao_melhoria,
                AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo,
                AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel,
                AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado,
                AcoesMelhoriasSolicitacoes.status as status_acoes_melhorias_solicitacoes,
                AcoesMelhoriasSolicitacoes.novo_prazo,
                AcoesMelhoriasSolicitacoes.justificativa_solicitacao,
                AcoesMelhoriasSolicitacoes.justificativa_recusa,
                AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao,
                AcoesMelhoriasSolicitacoes.codigo_usuario_alteracao,
                AcoesMelhoriasSolicitacoes.data_inclusao as data_inclusao_solicitacoes,
                AcoesMelhoriasSolicitacoes.data_alteracao,
                AcoesMelhoriasSolicitacoes.data_remocao,
                UsuarioInclusaoSolicitacao.nome AS nome_usuario_inclusao,
                NovoUsuarioResponsavel.nome AS nome_novo_usuario_responsavel,
                UsuarioSolicitado.nome AS nome_usuario_solicitado
            from ctePosAnexos as ctePosAnexos
                LEFT JOIN pos_obs_observacao_acao_melhoria PosObsObservacaoAcaoMelhoria on PosObsObservacaoAcaoMelhoria.obs_observacao_id = ctePosAnexos.codigo_observacao
                left JOIN acoes_melhorias AcoesMelhorias on AcoesMelhorias.codigo = PosObsObservacaoAcaoMelhoria.acoes_melhoria_id
                LEFT JOIN acoes_melhorias_status AcoesMelhoriasStatus on AcoesMelhoriasStatus.codigo = AcoesMelhorias.codigo_acoes_melhorias_status
                LEFT JOIN acoes_melhorias_tipo AcoesMelhoriasTipo on AcoesMelhoriasTipo.codigo = AcoesMelhorias.codigo_acoes_melhorias_tipo
                LEFT JOIN pos_criticidade PosCriticidade on PosCriticidade.codigo = AcoesMelhorias.codigo_pos_criticidade
                LEFT JOIN usuario UsuarioResponsavel on UsuarioResponsavel.codigo = ctePosAnexos.codigo_usuario_status
                LEFT JOIN usuario UsuarioIdentificador on UsuarioIdentificador.codigo = AcoesMelhorias.codigo_usuario_identificador
                LEFT JOIN usuarios_dados UsuariosDados on UsuariosDados.codigo_usuario = UsuarioIdentificador.codigo
                LEFT JOIN origem_ferramentas OrigemFerramentas on OrigemFerramentas.codigo = AcoesMelhorias.codigo_origem_ferramenta
                LEFT JOIN cliente Cliente on Cliente.codigo = AcoesMelhorias.codigo_cliente_observacao
                LEFT JOIN cliente_endereco Endereco on Endereco.codigo_cliente = Cliente.codigo
                LEFT JOIN acoes_melhorias_solicitacoes AcoesMelhoriasSolicitacoes on AcoesMelhoriasSolicitacoes.codigo_acao_melhoria = AcoesMelhorias.codigo
                LEFT JOIN usuario UsuarioInclusaoSolicitacao on UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao
                LEFT JOIN usuario NovoUsuarioResponsavel on NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel
                LEFT JOIN usuario UsuarioSolicitado on UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado
            WHERE 1=1
            )

            select * from cteAcaoMelhoria
            ORDER BY
            cteAcaoMelhoria.codigo_observacao DESC
        ";

        // debug($sql);exit;

        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($sql)->fetchAll('assoc');

        $endTimeSql = microtime(true);

        $sqlTime = $endTimeSql - $startTimeSql;

        if (!empty($dados)) {
            $startTimeTransformacao = microtime(true);
            $transforme = $this->responseTransformacaoDados($dados);
            $endTimeTransformacao = microtime(true);

            $transformacaoTime = $endTimeTransformacao - $startTimeTransformacao;
            $dados = $transforme;

            $dados['Timers'] = [
                'sql' => $sqlTime,
                'transformacao' => $transformacaoTime
            ];

            return $dados;
        } else {
            return $dados = [];
        }
    }

    public function cteobterListaObservacoes($conditions)
    {

        $this->PosObsObservacaoTable    = TableRegistry::getTableLocator()->get('PosObsObservacao');

        $where = $this->montaConditions($conditions);

        $joins = "";

        if (array_key_exists('FuncionarioSetorCargo.codigo_setor', $conditions)) {

            $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

            $joins = "LEFT JOIN funcionario_setores_cargos FuncionarioSetoresCargos ON FuncionarioSetoresCargos.codigo_cliente = '" . $setorECargo->codigo_cliente . "' AND FuncionarioSetoresCargos.codigo_setor = '" . $setorECargo->codigo_setor . "' AND FuncionarioSetoresCargos.data_fim is NULL";
        }

        $sql = "
            WITH CtePosObsObservacao AS (
                
                SELECT                      
                    PosObsObservacao.codigo AS [codigo_observacao],
                    PosObsObservacao.codigo_unidade as codigo_unidade,
                    PosObsObservacao.codigo_usuario_status,
                    PosObsObservacao.data_observacao AS [data_observacao],
                    AcoesMelhoriasStatusResponsavel.codigo AS [status],
                    AcoesMelhoriasStatusResponsavel.cor AS [status_cor],
                    AcoesMelhoriasStatusResponsavel.descricao AS [status_descricao],
                    PosCriticidade.codigo AS [criticidade],
                    PosCriticidade.cor AS [criticidade_cor],
                    PosCriticidade.descricao AS [criticidade_descricao],
                    PosObsLocal.codigo AS [codigo_local_observacao],
                    PosObsLocal.descricao AS [local_observacao],
                    PosCategorias.codigo AS [codigo_pos_categoria],
                    PosCategorias.descricao AS [categoria_descricao],
                    PosObsObservacao.codigo_status AS [codigo_status],
                    UsuarioResponsavel.codigo AS [usuario_responsavel_codigo],
                    PosObsObservacao.codigo AS [codigo_cliente],
                    Endereco.codigo AS [localidades_codigo_local_empresa],
                    Endereco.logradouro AS [localidades_codigo_local_empresa_logradouro],
                    Endereco.numero AS [localidades_codigo_local_empresa_numero],
                    Endereco.bairro AS [localidades_codigo_local_empresa_bairro],
                    Endereco.cidade AS [localidades_codigo_local_empresa_cidade],
                    Endereco.estado_descricao AS [localidades_codigo_local_empresa_estado_descricao],
                    PosObsLocais.codigo_localidade AS [localidades_codigo_localidade],
                    PosObsLocais.codigo_cliente_bu AS [localidades_codigo_bu],
                    PosObsLocais.codigo_cliente_opco AS [localidades_codigo_opco],
                    PosObsObservacao.data_observacao AS [observacao_data],
                    PosObsObservacao.data_observacao AS [observacao_hora],
                    PosObsObservacao.descricao_usuario_observou AS [descricao_usuario_observou],
                    PosObsObservacao.descricao_usuario_acao AS [descricao_usuario_acao],
                    PosObsObservacao.descricao_usuario_sugestao AS [descricao_usuario_sugestao],
                    PosObsObservacao.descricao AS [descricao],
                    PosObsObservacao.codigo_local_descricao AS [descricao_codigo_local],
                    PosObsObservacao.observacao_criticidade AS [observacao_criticidade],
                    PosObsObservacao.qualidade_avaliacao AS [qualidade_avaliacao],
                    PosObsObservacao.qualidade_descricao_complemento AS [qualidade_descricao_complemento],
                    PosObsObservacao.qualidade_descricao_participantes_tratativa AS [qualidade_descricao_participantes_tratativa]
                FROM
                    pos_obs_observacao PosObsObservacao
                    INNER JOIN pos_obs_locais PosObsLocais ON PosObsLocais.codigo_pos_obs_observacao = PosObsObservacao.codigo
                    INNER JOIN acoes_melhorias_status AcoesMelhoriasStatus ON AcoesMelhoriasStatus.codigo = PosObsObservacao.codigo_status
                    LEFT JOIN acoes_melhorias_status AcoesMelhoriasStatusResponsavel ON AcoesMelhoriasStatusResponsavel.codigo = PosObsObservacao.codigo_status_responsavel
                    LEFT JOIN pos_categorias PosCategorias ON PosCategorias.codigo = PosObsObservacao.codigo_pos_categoria
                    LEFT JOIN pos_criticidade PosCriticidade ON PosCriticidade.codigo = PosObsObservacao.observacao_criticidade
                    LEFT JOIN pos_obs_local PosObsLocal ON PosObsObservacao.codigo_pos_obs_local = PosObsLocal.codigo
                    LEFT JOIN usuario UsuarioResponsavel ON PosObsObservacao.codigo_usuario_status = UsuarioResponsavel.codigo
                    LEFT JOIN cliente Cliente ON PosObsObservacao.codigo_cliente = Cliente.codigo
                    LEFT JOIN cliente_endereco Endereco ON Cliente.codigo = Endereco.codigo_cliente
                    {$joins}
                WHERE 1=1
                {$where}                    
            ),

            CtePosObsRiscos AS (

                SELECT
                    CtePosObsObservacao.*,
                    PosObsRiscos.codigo AS [PosObsRiscos__codigo],
                    RiscosTipo.codigo AS [codigo_risco_tipo],
                    RiscosTipo.icone AS [risco_tipo_icone],
                    RiscosTipo.cor AS [risco_tipo_cor],
                    RiscosTipo.descricao AS [descricao_risco_tipo],
                    RiscosImpactos.codigo AS [codigo_impacto],
                    RiscosImpactos.descricao AS [descricao_impacto],
                    PerigosAspectos.codigo AS [codigo_aspecto],
                    PerigosAspectos.descricao AS [descricao_aspecto]
                FROM
                CtePosObsObservacao as CtePosObsObservacao
                    LEFT JOIN pos_obs_riscos PosObsRiscos ON PosObsRiscos.codigo_pos_obs_observacao = CtePosObsObservacao.codigo_observacao
                    LEFT JOIN riscos_tipo RiscosTipo ON PosObsRiscos.codigo_arrt = (RiscosTipo.codigo)
                    LEFT JOIN riscos_impactos RiscosImpactos ON PosObsRiscos.codigo_arrtpa_ri = (RiscosImpactos.codigo)
                    LEFT JOIN perigos_aspectos PerigosAspectos ON PosObsRiscos.codigo_arrt_pa = (PerigosAspectos.codigo)
                WHERE 1=1
            ),

            cteParticipantes AS (
                SELECT
                    CtePosObsRiscos.*,
                    PosObsParticipantes.codigo AS [codigo],
                    PosObsParticipantes.codigo_usuario AS [codigo_usuario],
                    UsuarioResponsavel.nome AS [nome],
                    UsuariosDados.avatar AS [avatar]
                FROM CtePosObsRiscos as CtePosObsRiscos 
                    LEFT JOIN pos_obs_participantes PosObsParticipantes ON PosObsParticipantes.codigo_pos_obs_observacao = CtePosObsRiscos.codigo_observacao
                    LEFT JOIN usuario UsuarioResponsavel ON PosObsParticipantes.codigo_usuario = (UsuarioResponsavel.codigo)
                    LEFT JOIN usuario UsuarioIdentificador ON PosObsParticipantes.codigo_usuario = (UsuarioIdentificador.codigo)
                    LEFT JOIN usuarios_dados UsuariosDados ON UsuarioIdentificador.codigo = (UsuariosDados.codigo_usuario)
                WHERE 1=1
            ),

            ctePosAnexos AS (

                SELECT
                    cteParticipantes.*,
                    PosObsAnexos.codigo AS [codigo_anexo],
                    PosAnexos.arquivo_url AS [arquivo]
                FROM cteParticipantes as cteParticipantes 
                    LEFT JOIN pos_obs_anexos PosObsAnexos on PosObsAnexos.codigo_pos_obs_observacao = cteParticipantes.codigo_observacao
                    LEFT JOIN pos_anexos PosAnexos ON (
                        PosAnexos.codigo_pos_ferramenta = 3
                        AND PosObsAnexos.codigo_pos_anexo = (PosAnexos.codigo)
                        AND PosObsAnexos.ativo = 1
                        AND PosAnexos.ativo = 1
                    )
                WHERE 1=1
            ),

            cteAcaoMelhoria AS (


            select
                ctePosAnexos.*,
                AcoesMelhorias.codigo as cte_codigo_acao_melhoria,
                AcoesMelhorias.prazo,
                AcoesMelhorias.descricao_acao,
                AcoesMelhorias.descricao_desvio,
                AcoesMelhorias.descricao_local_acao,
                AcoesMelhorias.formulario_resposta,
                AcoesMelhorias.data_conclusao,
                AcoesMelhoriasStatus.codigo as codigo_status_acao_melhoria,
                AcoesMelhoriasStatus.cor,
                AcoesMelhoriasStatus.descricao as descricao_melhorias_status,
                AcoesMelhoriasTipo.codigo as codigo_tipo_acao_melhoria,
                AcoesMelhoriasTipo.descricao as descricao_tipo,
                AcoesMelhorias.data_inclusao,
                PosCriticidade.codigo as codigo_pos_criticidade,
                PosCriticidade.descricao as descricao_pos_criticidade,
                PosCriticidade.cor as cor_pos_criticidade,
                UsuarioResponsavel.codigo as codigo_usuario_responsavel,
                UsuarioResponsavel.nome as nome_usuario_responsavel,
                OrigemFerramentas.codigo as codigo_origem_ferramenta,
                OrigemFerramentas.codigo_cliente as codigo_cliente_origem_ferramenta,
                OrigemFerramentas.descricao as desc_origem_ferramenta,
                UsuarioIdentificador.codigo as codigo_usuario_identificador,
                UsuarioIdentificador.nome as nome_usuario_identificador,
                UsuarioIdentificador.codigo_cliente as codigo_cliente_identificador,
                UsuariosDados.codigo as codigo_usuarios_dados,
                UsuariosDados.avatar as avatar_usuarios_dados,
                Cliente.codigo as codigo_cliente_acao_melhoria,
                Cliente.razao_social,
                Cliente.nome_fantasia,
                Endereco.codigo as codigo_endereco,
                Endereco.cep,
                Endereco.logradouro,
                Endereco.numero,
                Endereco.bairro,
                Endereco.cidade,
                Endereco.estado_descricao,
                Endereco.complemento,
                CONCAT(
                    Endereco.logradouro, ', ', Endereco.numero, ' - ', Endereco.bairro, ' - ', Endereco.cidade, '/', Endereco.estado_descricao
                ) as endereco_completo_localidade,
                AcoesMelhoriasSolicitacoes.codigo as codigo_acoes_melhorias_solicitacoes,
                AcoesMelhoriasSolicitacoes.codigo_acao_melhoria,
                AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo,
                AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel,
                AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado,
                AcoesMelhoriasSolicitacoes.status as status_acoes_melhorias_solicitacoes,
                AcoesMelhoriasSolicitacoes.novo_prazo,
                AcoesMelhoriasSolicitacoes.justificativa_solicitacao,
                AcoesMelhoriasSolicitacoes.justificativa_recusa,
                AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao,
                AcoesMelhoriasSolicitacoes.codigo_usuario_alteracao,
                AcoesMelhoriasSolicitacoes.data_inclusao as data_inclusao_solicitacoes,
                AcoesMelhoriasSolicitacoes.data_alteracao,
                AcoesMelhoriasSolicitacoes.data_remocao,
                UsuarioInclusaoSolicitacao.nome AS nome_usuario_inclusao,
                NovoUsuarioResponsavel.nome AS nome_novo_usuario_responsavel,
                UsuarioSolicitado.nome AS nome_usuario_solicitado
            from ctePosAnexos as ctePosAnexos
                LEFT JOIN pos_obs_observacao_acao_melhoria PosObsObservacaoAcaoMelhoria on PosObsObservacaoAcaoMelhoria.obs_observacao_id = ctePosAnexos.codigo_observacao
                left JOIN acoes_melhorias AcoesMelhorias on AcoesMelhorias.codigo = PosObsObservacaoAcaoMelhoria.acoes_melhoria_id
                LEFT JOIN acoes_melhorias_status AcoesMelhoriasStatus on AcoesMelhoriasStatus.codigo = AcoesMelhorias.codigo_acoes_melhorias_status
                LEFT JOIN acoes_melhorias_tipo AcoesMelhoriasTipo on AcoesMelhoriasTipo.codigo = AcoesMelhorias.codigo_acoes_melhorias_tipo
                LEFT JOIN pos_criticidade PosCriticidade on PosCriticidade.codigo = AcoesMelhorias.codigo_pos_criticidade
                LEFT JOIN usuario UsuarioResponsavel on UsuarioResponsavel.codigo = ctePosAnexos.codigo_usuario_status
                LEFT JOIN usuario UsuarioIdentificador on UsuarioIdentificador.codigo = AcoesMelhorias.codigo_usuario_identificador
                LEFT JOIN usuarios_dados UsuariosDados on UsuariosDados.codigo_usuario = UsuarioIdentificador.codigo
                LEFT JOIN origem_ferramentas OrigemFerramentas on OrigemFerramentas.codigo = AcoesMelhorias.codigo_origem_ferramenta
                LEFT JOIN cliente Cliente on Cliente.codigo = AcoesMelhorias.codigo_cliente_observacao
                LEFT JOIN cliente_endereco Endereco on Endereco.codigo_cliente = Cliente.codigo
                LEFT JOIN acoes_melhorias_solicitacoes AcoesMelhoriasSolicitacoes on AcoesMelhoriasSolicitacoes.codigo_acao_melhoria = AcoesMelhorias.codigo
                LEFT JOIN usuario UsuarioInclusaoSolicitacao on UsuarioInclusaoSolicitacao.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_inclusao
                LEFT JOIN usuario NovoUsuarioResponsavel on NovoUsuarioResponsavel.codigo = AcoesMelhoriasSolicitacoes.codigo_novo_usuario_responsavel
                LEFT JOIN usuario UsuarioSolicitado on UsuarioSolicitado.codigo = AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado
            WHERE 1=1
            )

            select * from cteAcaoMelhoria
            ORDER BY
            cteAcaoMelhoria.codigo_observacao DESC
        ";

        // debug($sql);exit;

        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($sql)->fetchAll('assoc');

        if (!empty($dados)) {
            $transforme = $this->responseTransformacaoDados($dados);
            $dados = $transforme;
            $dados['count'] = count($dados);
            return $dados;
        } else {
            return $dados = [];
        }
    }

    public function obterListagem($conditions)
    {

        //$this->PosObsObservacaoTable    = TableRegistry::getTableLocator()->get('PosObsObservacao');

        $where = $this->montaConditions($conditions);

        $joins = "";

        if (array_key_exists('FuncionarioSetorCargo.codigo_setor', $conditions)) {

            $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

            $joins = "LEFT JOIN funcionario_setores_cargos FuncionarioSetoresCargos ON FuncionarioSetoresCargos.codigo_cliente = '" . $setorECargo->codigo_cliente . "' AND FuncionarioSetoresCargos.codigo_setor = '" . $setorECargo->codigo_setor . "' AND FuncionarioSetoresCargos.data_fim is NULL";
        }

        $subSqlCountAcoesMelhoria = "SELECT COUNT(*)
                                    FROM acoes_melhorias am 
                                    JOIN pos_obs_observacao_acao_melhoria pooam ON pooam.acoes_melhoria_id = am.codigo 
                                    JOIN pos_obs_observacao poo ON poo.codigo = pooam.obs_observacao_id 
                                    WHERE am.codigo_usuario_responsavel = {$this->codigo_usuario_autenticado}
                                    AND pooam.obs_observacao_id = PosObsObservacao.codigo";

        $subSqlObservador = "SELECT tmpObservador.nome
                            FROM (
                                SELECT TOP 1                                                                                   
                                        PosObsParticipantes.codigo,
                                        UsuarioResponsavel.nome AS [nome]                             
                                FROM pos_obs_riscos PosObsRiscos  
                                JOIN pos_obs_participantes PosObsParticipantes ON PosObsParticipantes.codigo_pos_obs_observacao = PosObsRiscos.codigo_pos_obs_observacao
                                JOIN usuario UsuarioResponsavel ON PosObsParticipantes.codigo_usuario = (UsuarioResponsavel.codigo)
                                JOIN usuario UsuarioIdentificador ON PosObsParticipantes.codigo_usuario = (UsuarioIdentificador.codigo)
                                LEFT JOIN usuarios_dados UsuariosDados ON UsuarioIdentificador.codigo = (UsuariosDados.codigo_usuario)
                                WHERE PosObsRiscos.codigo_pos_obs_observacao = PosObsObservacao.codigo
                                AND PosObsParticipantes.ativo = 1
                                ORDER BY PosObsParticipantes.codigo DESC
                            ) AS tmpObservador";

        $sql = "SELECT 
                PosObsObservacao.codigo AS [codigo_observacao],
                PosObsObservacao.codigo_unidade as codigo_unidade,
                PosObsObservacao.codigo_usuario_status,
                PosObsObservacao.data_observacao AS [data_observacao],
                AcoesMelhoriasStatusResponsavel.codigo AS [status],
                AcoesMelhoriasStatusResponsavel.cor AS [status_cor],
                AcoesMelhoriasStatusResponsavel.descricao AS [status_descricao],
                PosCriticidade.codigo AS [criticidade],
                PosCriticidade.cor AS [criticidade_cor],
                PosCriticidade.descricao AS [criticidade_descricao],
                PosObsLocal.codigo AS [codigo_local_observacao],
                PosObsLocal.descricao AS [local_observacao],
                PosCategorias.codigo AS [codigo_pos_categoria],
                PosCategorias.descricao AS [categoria_descricao],
                PosObsObservacao.codigo_status AS [codigo_status],
                UsuarioResponsavel.codigo AS [usuario_responsavel_codigo],
                UsuarioResponsavel.nome AS [usuario_responsavel_nome],
                PosObsObservacao.codigo AS [codigo_cliente],
                Endereco.codigo AS [localidades_codigo_local_empresa],
                Endereco.logradouro AS [localidades_codigo_local_empresa_logradouro],
                Endereco.numero AS [localidades_codigo_local_empresa_numero],
                Endereco.bairro AS [localidades_codigo_local_empresa_bairro],
                Endereco.cidade AS [localidades_codigo_local_empresa_cidade],
                Endereco.estado_descricao AS [localidades_codigo_local_empresa_estado_descricao],
                PosObsLocais.codigo_localidade AS [localidades_codigo_localidade],
                PosObsLocais.codigo_cliente_bu AS [localidades_codigo_bu],
                PosObsLocais.codigo_cliente_opco AS [localidades_codigo_opco],
                PosObsObservacao.data_observacao AS [observacao_data],
                PosObsObservacao.data_observacao AS [observacao_hora],
                PosObsObservacao.descricao_usuario_observou AS [descricao_usuario_observou],
                PosObsObservacao.descricao_usuario_acao AS [descricao_usuario_acao],
                PosObsObservacao.descricao_usuario_sugestao AS [descricao_usuario_sugestao],
                PosObsObservacao.descricao AS [descricao],
                PosObsObservacao.codigo_local_descricao AS [descricao_codigo_local],
                PosObsObservacao.observacao_criticidade AS [observacao_criticidade],
                PosObsObservacao.qualidade_avaliacao AS [qualidade_avaliacao],
                PosObsObservacao.qualidade_descricao_complemento AS [qualidade_descricao_complemento],
                PosObsObservacao.qualidade_descricao_participantes_tratativa AS [qualidade_descricao_participantes_tratativa],
                (
                    {$subSqlObservador}
                ) AS [observador],
                (
                    {$subSqlCountAcoesMelhoria}
                ) AS [ams_responsabilidade]
            FROM
                pos_obs_observacao PosObsObservacao
                INNER JOIN pos_obs_locais PosObsLocais ON PosObsLocais.codigo_pos_obs_observacao = PosObsObservacao.codigo
                INNER JOIN acoes_melhorias_status AcoesMelhoriasStatus ON AcoesMelhoriasStatus.codigo = PosObsObservacao.codigo_status
                LEFT JOIN acoes_melhorias_status AcoesMelhoriasStatusResponsavel ON AcoesMelhoriasStatusResponsavel.codigo = PosObsObservacao.codigo_status_responsavel
                LEFT JOIN pos_categorias PosCategorias ON PosCategorias.codigo = PosObsObservacao.codigo_pos_categoria
                LEFT JOIN pos_criticidade PosCriticidade ON PosCriticidade.codigo = PosObsObservacao.observacao_criticidade
                LEFT JOIN pos_obs_local PosObsLocal ON PosObsObservacao.codigo_pos_obs_local = PosObsLocal.codigo
                LEFT JOIN usuario UsuarioResponsavel ON PosObsObservacao.codigo_usuario_status = UsuarioResponsavel.codigo
                LEFT JOIN cliente Cliente ON PosObsObservacao.codigo_cliente = Cliente.codigo
                LEFT JOIN cliente_endereco Endereco ON Cliente.codigo = Endereco.codigo_cliente
                {$joins}
            WHERE 1=1
            {$where}";

        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($sql)->fetchAll('assoc');

        if (!empty($dados)) {
            $transforme = $this->responseTransformaDadosListagem($dados);
            $dados = $transforme;
            return $dados;
        } else {
            return $dados = [];
        }
    }

    private function responseTransformaDadosListagem($data)
    {

        $dados_form = array();
        $riscos = [];
        $participantes = [];
        $anexos = [];
        $acoesMelhorias = [];
        $solicitacoes = [];

        foreach ($data as $key => $dados) {

            $dados = $this->utf8DecodeColumns($dados);

            $observacao = (new Time($dados['observacao_data']));
            $observacao_data = $observacao->i18nFormat('dd/MM/yyyy');
            $observacao_hora = $observacao->i18nFormat('HH:mm');

            $local_empresa_descricao = null;

            if (!empty($dados['localidades_codigo_local_empresa_logradouro'])) {

                $v = $dados['localidades_codigo_local_empresa_logradouro'];

                if (!empty(Comum::soNumero($dados['localidades_codigo_local_empresa_numero']))) {
                    $v = $v . ' ,' . Comum::soNumero($dados['localidades_codigo_local_empresa_numero']);
                }

                if (!empty($dados['localidades_codigo_local_empresa_bairro'])) {
                    $v = $v . ' - ' . $dados['localidades_codigo_local_empresa_bairro'];
                }

                if (!empty($dados['localidades_codigo_local_empresa_estado_descricao'])) {
                    $v = $v . ', ' . $dados['localidades_codigo_local_empresa_estado_descricao'];
                }

                $local_empresa_descricao = $v;
            }

            $dados_form[$key] = [
                'codigo_observacao'           => $dados['codigo_observacao'],
                'codigo_unidade'              => $dados['codigo_unidade'],
                'codigo_categoria_observacao' => $dados['codigo_pos_categoria'],
                'codigo_local_observacao'     => $dados['codigo_local_observacao'],
                'local_observacao'            => $dados['local_observacao'],
                'categoria_descricao'         => $dados['categoria_descricao'],
                'localidades'                 => [
                    "codigo_local_empresa"    => $this->soNumero($dados['localidades_codigo_local_empresa']),
                    "local_empresa_descricao" => $local_empresa_descricao,
                    "codigo_localidade"       => $this->soNumero($dados['localidades_codigo_localidade']),
                    "codigo_bu"               => $this->soNumero($dados['localidades_codigo_bu']),
                    "codigo_opco"             => $this->soNumero($dados['localidades_codigo_opco'])
                ],
                'observacao_data'            => $observacao_data,
                'observacao_hora'            => $observacao_hora,
                'descricao_usuario_observou' => $dados['descricao_usuario_observou'],
                'descricao_usuario_acao'     => $dados['descricao_usuario_acao'],
                'descricao_usuario_sugestao' => $dados['descricao_usuario_sugestao'],
                'descricao'                  => $dados['descricao'],
                'status_codigo'              => $dados['status'],
                'status_cor'                 => $dados['status_cor'],
                'status_descricao'           => $dados['status_descricao'],
                'criticidade_codigo'         => $dados['criticidade'],
                'criticidade_cor'            => $dados['criticidade_cor'],
                'criticidade_descricao'      => $dados['criticidade_descricao'],
                'observador'                 => $dados['observador'],
                'ams_responsabilidade'       => $dados['ams_responsabilidade'],
                'usuario_responsavel_codigo' => $dados['usuario_responsavel_codigo'],
                'usuario_responsavel_nome'   => $dados['usuario_responsavel_nome'],
            ];
        }

        /*** REMOVER DADOS OBS REPETIDOS */

        $dados_obs_repetidos = array();
        foreach ($dados_form as $dados) {
            $dados_obs_repetidos[] = $dados['codigo_observacao'];
        }

        $dados_form_ajustados = array_unique($dados_obs_repetidos); //remove os repetidos

        foreach ($dados_form_ajustados as $key => $dados) {
            foreach ($dados_form as $key1 => $dados_form_trat) {
                if ($dados == $dados_form_trat['codigo_observacao']) {
                    $dados_form_ajustados[$key] = $dados_form_trat;
                }
            }
        }

        $dados_form = $dados_form_ajustados; //array principal
        /*** FIM REMOVER DADOS OBS REPETIDOS */

        $dados_obs_observacao = [];
        foreach ($dados_form as $key => $dados) {
            $dados_obs_observacao[] = $dados;
        }


        return $dados_obs_observacao;
    }

    public function montaConditions($conditions)
    {

        $where = "";

        if (isset($conditions['codigo_unidade'])) {
            $where .= " AND PosObsObservacao.codigo_cliente = " . $this->obterCodigoMatrizPeloCodigoFilial($conditions['codigo_unidade']);
            unset($conditions['codigo_unidade']);
        }

        if (isset($conditions['status'])) {
            $where .= " AND AcoesMelhoriasStatus.codigo = " . $conditions['status'];
            unset($conditions['status']);
        } else {
            if (isset($conditions['status_in'])) {
                $status_in = implode(',', $conditions['status_in']);
                $where .= " AND AcoesMelhoriasStatus.codigo IN (" . $status_in . ")";
                unset($conditions['status_in']);
            } else {
                $where .= " AND AcoesMelhoriasStatus.codigo = 1";
            }
        }

        if (isset($conditions['codigo_observacao'])) {
            $where .= " AND PosObsObservacao.codigo = " . $conditions['codigo_observacao'];
            unset($conditions['codigo_observacao']);
        }

        if (isset($conditions['filtrar'])) {
            // ['autor'=>$autor, 'periodo_de'=>$de, 'periodo_ate'=>$ate];
            if (isset($conditions['filtrar']['autor'])) {

                if ($conditions['filtrar']['autor'] == 'usuario') {
                    $where .= " AND PosObsObservacao.codigo_usuario_inclusao = " . $this->codigo_usuario_autenticado;
                }

                if ($conditions['filtrar']['autor'] == 'area') {
                    $setorECargo = $this->obterSetorCargoUsuarioAutenticado();

                    if (!empty($setorECargo->codigo_setor)) {
                        $where .= " AND FuncionarioSetorCargo.codigo_setor = " . $setorECargo->codigo_setor;
                    }
                }
            }

            if (isset($conditions['filtrar']['periodo_de']) && isset($conditions['filtrar']['periodo_ate'])) {
                $data_inicial =  \DateTime::createFromFormat('Y-m-d H:i:s', $conditions['filtrar']['periodo_de'] . '00:00:00');
                $data_final =  \DateTime::createFromFormat('Y-m-d H:i:s', $conditions['filtrar']['periodo_ate'] . '23:59:59');

                if (!$data_inicial || !$data_final) {
                    throw new Exception("Erro na escolha da data");
                }

                $where .= " AND PosObsObservacao.data_observacao between '" . $data_inicial->format('Y-m-d H:i:s') . "' AND '" . $data_final->format('Y-m-d H:i:s') . "'";
            }

            unset($conditions['filtrar']);
        }

        return $where;
    }

    private function responseTransformacaoDados($data)
    {
        $dados_form = array();
        $riscos = [];
        $participantes = [];
        $anexos = [];
        $acoesMelhorias = [];
        $solicitacoes = [];

        foreach ($data as $key => $dados) {

            $dados = $this->utf8DecodeColumns($dados);

            $observacao = (new Time($dados['observacao_data']));
            $observacao_data = $observacao->i18nFormat('dd/MM/yyyy');
            $observacao_hora = $observacao->i18nFormat('HH:mm');

            $local_empresa_descricao = null;

            if (!empty($dados['localidades_codigo_local_empresa_logradouro'])) {

                $v = $dados['localidades_codigo_local_empresa_logradouro'];

                if (!empty(Comum::soNumero($dados['localidades_codigo_local_empresa_numero']))) {
                    $v = $v . ' ,' . Comum::soNumero($dados['localidades_codigo_local_empresa_numero']);
                }

                if (!empty($dados['localidades_codigo_local_empresa_bairro'])) {
                    $v = $v . ' - ' . $dados['localidades_codigo_local_empresa_bairro'];
                }

                if (!empty($dados['localidades_codigo_local_empresa_estado_descricao'])) {
                    $v = $v . ', ' . $dados['localidades_codigo_local_empresa_estado_descricao'];
                }

                $local_empresa_descricao = $v;
            }

            // const UTF8_DECODE_COLUMNS = [
            //     'criticidade_descricao',
            //     'local_observacao',
            //     'categoria_descricao',
            //     'localidades_codigo_local_empresa_cidade',
            //     'descricao_risco_tipo',
            //     'descricao_impacto',
            //     'descricao_aspecto',
            //     'endereco_completo_localidade',
            // ];

            $dados_form[$key] = [
                'codigo_observacao'           => $dados['codigo_observacao'],
                'codigo_unidade'              => $dados['codigo_unidade'],
                'codigo_categoria_observacao' => $dados['codigo_pos_categoria'],
                'codigo_local_observacao'     => $dados['codigo_local_observacao'],
                'local_observacao'            => $dados['local_observacao'],
                'categoria_descricao'         => $dados['categoria_descricao'],
                'localidades'                 => [
                    "codigo_local_empresa"    => $this->soNumero($dados['localidades_codigo_local_empresa']),
                    "local_empresa_descricao" => $local_empresa_descricao,
                    "codigo_localidade"       => $this->soNumero($dados['localidades_codigo_localidade']),
                    "codigo_bu"               => $this->soNumero($dados['localidades_codigo_bu']),
                    "codigo_opco"             => $this->soNumero($dados['localidades_codigo_opco'])
                ],
                'observacao_data'            => $observacao_data,
                'observacao_hora'            => $observacao_hora,
                'descricao_usuario_observou' => $dados['descricao_usuario_observou'],
                'descricao_usuario_acao'     => $dados['descricao_usuario_acao'],
                'descricao_usuario_sugestao' => $dados['descricao_usuario_sugestao'],
                'descricao'                  => $dados['descricao'],
                'status_codigo'              => $dados['status'],
                'status_cor'                 => $dados['status_cor'],
                'status_descricao'           => $dados['status_descricao'],
                'criticidade_codigo'         => $dados['criticidade'],
                'criticidade_cor'            => $dados['criticidade_cor'],
                'criticidade_descricao'      => $dados['criticidade_descricao'],
            ];

            $riscos[$key] = [
                "codigo_observacao"    => $dados['codigo_observacao'],
                "codigo"               => $dados['PosObsRiscos__codigo'],
                "codigo_risco_tipo"    => $dados['codigo_risco_tipo'],
                "risco_tipo_icone"     => $dados['risco_tipo_icone'],
                "risco_tipo_cor"       => $dados['risco_tipo_cor'],
                "descricao_risco_tipo" => $dados['descricao_risco_tipo'],
                "codigo_impacto"       => $dados['codigo_impacto'],
                "descricao_impacto"    => $dados['descricao_impacto'],
                "codigo_aspecto"       => $dados['codigo_aspecto'],
                "descricao_aspecto"    => $dados['descricao_aspecto'],
            ];

            $participantes[$key] = [
                "codigo_observacao"    => $dados['codigo_observacao'],
                "codigo"               => $dados['codigo'],
                "codigo_usuario"       => $dados['codigo_usuario'],
                "nome"                 => $dados['nome'],
                "avatar"               => $dados['avatar'],
            ];

            $anexos[$key] = [
                "codigo_observacao"    => $dados['codigo_observacao'],
                "codigo_anexo"         => $dados['codigo_anexo'],
                "arquivo"              => $dados['arquivo'],
            ];

            $acoesMelhorias[$key] = [
                "codigo_observacao"            => $dados['codigo_observacao'],
                "codigo"                       => $dados['cte_codigo_acao_melhoria'],
                "descricao_acao"               => $dados['descricao_acao'],
                "prazo"                        => $dados['prazo'],
                "descricao_desvio"             => $dados['descricao_desvio'],
                "descricao_local_acao"         => $dados['descricao_local_acao'],
                "formulario_resposta"          => $dados['formulario_resposta'],
                "data_conclusao"               => $dados['data_conclusao'],
                "endereco_completo_localidade" => $dados['endereco_completo_localidade'],
                "localidade"                   => [
                    "codigo"        => $dados['codigo_cliente_acao_melhoria'],
                    "razao_social"  => $dados['razao_social'],
                    "nome_fantasia" => $dados['nome_fantasia'],
                    "endereco"      => [
                        "codigo"           => $dados['codigo_endereco'],
                        "cep"              => $dados['cep'],
                        "logradouro"       => $dados['logradouro'],
                        "numero"           => $dados['numero'],
                        "bairro"           => $dados['bairro'],
                        "cidade"           => $dados['cidade'],
                        "estado_descricao" => $dados['estado_descricao'],
                        "complemento"      => $dados['complemento'],
                    ],
                ],
                "origem_ferramenta" => [
                    "codigo" => $dados['codigo_origem_ferramenta'],
                    "codigo_cliente" => $dados['codigo_cliente_origem_ferramenta'],
                    "descricao" => $dados['desc_origem_ferramenta'],
                ],
                "identificador" => [
                    "codigo" => $dados['codigo_usuario_identificador'],
                    "nome" => $dados['nome_usuario_identificador'],
                    "dados" => [
                        "codigo" => $dados['codigo_usuarios_dados'],
                        "avatar" => $dados['avatar_usuarios_dados'],
                    ],
                ],
                "responsavel" => [
                    "codigo" => $dados['codigo_usuario_responsavel'],
                    "nome" => $dados['nome_usuario_responsavel'],
                ],
                "criticidade" => [
                    "codigo" =>  $dados['codigo_pos_criticidade'],
                    "descricao" => $dados['descricao_pos_criticidade'],
                    "cor" => $dados['cor_pos_criticidade'],
                ],
                "tipo" => [
                    "codigo" => $dados['codigo_tipo_acao_melhoria'],
                    "descricao" => $dados['descricao_tipo'],
                ],
                "status" => [
                    "codigo" => $dados['codigo_status_acao_melhoria'],
                    "cor" => $dados['cor'],
                    "descricao" => $dados['descricao_melhorias_status'],
                ]
            ];

            $solicitacoes[$key] = [
                "codigo_observacao"                     => $dados['codigo_observacao'],
                'codigo'                                => $dados['codigo_acoes_melhorias_solicitacoes'],
                'codigo_acao_melhoria'                  => $dados['cte_codigo_acao_melhoria'],
                'codigo_acao_melhoria_solicitacao_tipo' => $dados['codigo_acao_melhoria_solicitacao_tipo'],
                'codigo_novo_usuario_responsavel'       => $dados['codigo_novo_usuario_responsavel'],
                'codigo_usuario_solicitado'             => $dados['codigo_usuario_solicitado'],
                'status'                                => $dados['status_acoes_melhorias_solicitacoes'],
                'novo_prazo'                            => $dados['novo_prazo'],
                'justificativa_solicitacao'             => $dados['justificativa_solicitacao'],
                'justificativa_recusa'                  => $dados['justificativa_recusa'],
                'codigo_usuario_inclusao'               => $dados['codigo_usuario_inclusao'],
                'codigo_usuario_alteracao'              => $dados['codigo_usuario_alteracao'],
                'data_inclusao'                         => $dados['data_inclusao_solicitacoes'],
                'data_alteracao'                        => $dados['data_alteracao'],
                'data_remocao'                          => $dados['data_remocao'],
                'nome_usuario_inclusao'                 => $dados['nome_usuario_inclusao'],
                'nome_novo_usuario_responsavel'         => $dados['nome_novo_usuario_responsavel'],
                'nome_usuario_solicitado'               => $dados['nome_usuario_solicitado'],
            ];
        }

        /*** REMOVER DADOS OBS REPETIDOS */

        $dados_obs_repetidos = array();
        foreach ($dados_form as $dados) {
            $dados_obs_repetidos[] = $dados['codigo_observacao'];
        }

        $dados_form_ajustados = array_unique($dados_obs_repetidos); //remove os repetidos

        foreach ($dados_form_ajustados as $key => $dados) {
            foreach ($dados_form as $key1 => $dados_form_trat) {
                if ($dados == $dados_form_trat['codigo_observacao']) {
                    $dados_form_ajustados[$key] = $dados_form_trat;
                }
            }
        }

        $dados_form = $dados_form_ajustados; //array principal
        /*** FIM REMOVER DADOS OBS REPETIDOS */

        /*** REMOVER RISCOS REPETIDOS */

        $riscos_repetidos = array();
        foreach ($riscos as $dados) {
            $riscos_repetidos[] = $dados['codigo'];
        }

        $dados_riscos_ajustados = array_unique($riscos_repetidos);
        foreach ($dados_riscos_ajustados as $key => $dados) {
            foreach ($riscos as $key1 => $dados_riscos_trat) {
                if ($dados == $dados_riscos_trat['codigo']) {
                    $dados_riscos_ajustados[$key] = $dados_riscos_trat;
                }
            }
        }

        $participantes_repetidos = array();
        foreach ($participantes as $dados) {
            $participantes_repetidos[] = $dados['codigo'];
        }

        $dados_participantes_ajustados = array_unique($participantes_repetidos);
        foreach ($dados_participantes_ajustados as $key => $dados) {
            foreach ($participantes as $key1 => $dados_participantes_trat) {
                if ($dados == $dados_participantes_trat['codigo']) {
                    $dados_participantes_ajustados[$key] = $dados_participantes_trat;
                }
            }
        }

        $anexos_repetidos = array();
        foreach ($anexos as $dados) {
            $anexos_repetidos[] = $dados['codigo_anexo'];
        }

        $dados_anexos_ajustados = array_unique($anexos_repetidos);
        foreach ($dados_anexos_ajustados as $key => $dados) {
            foreach ($anexos as $key1 => $dados_anexos_trat) {
                if ($dados == $dados_anexos_trat['codigo_anexo']) {
                    $dados_anexos_ajustados[$key] = $dados_anexos_trat;
                }
            }
        }

        $acoes_melhorias_repetidas = array();
        foreach ($acoesMelhorias as $dados) {
            $acoes_melhorias_repetidas[] = $dados['codigo'];
        }

        $acoes_melhorias_ajustados = array_unique($acoes_melhorias_repetidas);
        foreach ($acoes_melhorias_ajustados as $key => $dados) {
            foreach ($acoesMelhorias as $key1 => $dados_acoes_melhorias_trat) {
                if ($dados == $dados_acoes_melhorias_trat['codigo']) {
                    $acoes_melhorias_ajustados[$key] = $dados_acoes_melhorias_trat;
                }
            }
        }

        $solicitacoes_repetidas = array();
        foreach ($solicitacoes as $dados) {
            $solicitacoes_repetidas[] = $dados['codigo'];
        }

        $solicitacoes_ajustadas = array_unique($solicitacoes_repetidas);
        foreach ($solicitacoes_ajustadas as $key => $dados) {
            foreach ($solicitacoes as $key1 => $solicitacoes_trat) {
                if ($dados == $solicitacoes_trat['codigo']) {
                    $solicitacoes_ajustadas[$key] = $solicitacoes_trat;
                }
            }
        }


        /*** FIM REMOVER RISCOS REPETIDOS */

        foreach ($dados_form as $key1 => $dados_obs) {
            //percorrer os riscos
            foreach ($dados_riscos_ajustados as $key2 => $dados_riscos) {
                //achar o risco vinculado a observacao e adciona-la ao array principal
                if ($dados_obs['codigo_observacao'] == $dados_riscos['codigo_observacao']) {
                    unset($dados_riscos['codigo_observacao']);
                    $dados_form[$key1]['riscos'][] = $dados_riscos;
                }
            }

            foreach ($dados_participantes_ajustados as $key2 => $dados_participantes) {
                //achar os participantes vinculados a observacao e adciona-la ao array principal
                if ($dados_obs['codigo_observacao'] == $dados_participantes['codigo_observacao']) {
                    unset($dados_participantes['codigo_observacao']);
                    $dados_form[$key1]['observadores'][] = $dados_participantes;
                }
            }

            foreach ($dados_anexos_ajustados as $key2 => $dados_anexos) {
                //achar os anexos vinculados a observacao e adciona-la ao array principal
                if ($dados_obs['codigo_observacao'] == $dados_anexos['codigo_observacao']) {
                    unset($dados_anexos['codigo_observacao']);
                    $dados_form[$key1]['anexos'][] = $dados_anexos;
                }
            }

            foreach ($acoes_melhorias_ajustados as $key2 => $dados_acoes) {
                //achar as acoes melhorias vinculadas a observacao e adciona-la ao array principal
                if ($dados_obs['codigo_observacao'] == $dados_acoes['codigo_observacao']) {
                    unset($dados_acoes['codigo_observacao']);
                    $dados_form[$key1]['acoes_melhorias'][] = $dados_acoes;
                }
            }
        }

        foreach ($dados_form as $key1 => $dados_) {

            if (empty($dados_['riscos'])) {
                $dados_form[$key1]['riscos'] = [];
            } else {
                foreach ($dados_['riscos'] as $dados_rrr) {
                    if (empty($dados_rrr['codigo'])) {
                        $dados_form[$key1]['riscos'] = [];
                    }
                }
            }

            if (empty($dados_['observadores'])) {
                $dados_form[$key1]['observadores'] = [];
            } else {
                foreach ($dados_['observadores'] as $dados_rrr) {
                    if (empty($dados_rrr['codigo'])) {
                        $dados_form[$key1]['observadores'] = [];
                    }
                }
            }

            if (empty($dados_['anexos'])) {
                $dados_form[$key1]['anexos'] = [];
            } else {
                foreach ($dados_['anexos'] as $dados_rrr) {
                    if (empty($dados_rrr['codigo'])) {
                        $dados_form[$key1]['anexos'] = [];
                    }
                }
            }

            if (empty($dados_['acoes_melhorias'])) {
                $dados_form[$key1]['acoes_melhorias'] = [];
            } else {
                foreach ($dados_['acoes_melhorias'] as $dados_rrr) {
                    if (empty($dados_rrr['codigo'])) {
                        $dados_form[$key1]['acoes_melhorias'] = [];
                    }
                }
            }
        }

        foreach ($dados_form as $key1 => $dados) {
            foreach ($dados['acoes_melhorias'] as $key2 => $dados_acoes_m) {
                foreach ($solicitacoes_ajustadas as $key3 => $dados_solic) {
                    if ($dados_acoes_m['codigo'] == $dados_solic['codigo_acao_melhoria']) {
                        unset($dados_solic['codigo_observacao']);
                        $dados_form[$key1]['acoes_melhorias'][$key2]['solicitacoes'] = $dados_solic;
                    }
                }
            }
        }
        /**** FIM TRATAMENTO */

        $dados_obs_observacao = [];
        foreach ($dados_form as $key => $dados) {
            $dados_obs_observacao[] = $dados;
        }

        foreach ($dados_obs_observacao as $key => $dados) {
            foreach ($dados['acoes_melhorias'] as $key1 => $dados_acoes_melhorias) {
                if (!isset($dados_acoes_melhorias['solicitacoes'])) {
                    $dados_obs_observacao[$key]['acoes_melhorias'][$key1]['solicitacoes'] = [];
                }
            }
        }

        return $dados_obs_observacao;
    }
}
