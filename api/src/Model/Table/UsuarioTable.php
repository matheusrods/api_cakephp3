<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Log\Log;

/**
 * Usuario Model
 *
 * @property \App\Model\Table\UsuarioDadosTable&\Cake\ORM\Association\BelongsTo $UsuarioDados
 * @property \App\Model\Table\MultiEmpresaTable&\Cake\ORM\Association\BelongsToMany $MultiEmpresa
 *
 * @method \App\Model\Entity\Usuario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usuario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usuario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usuario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usuario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usuario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usuario findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioTable extends AppTable
{
    public $connection;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->connection = ConnectionManager::get('default');
        $this->setTable('usuario');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        // $this->belongsTo('UsuariosDados', [
        //     'foreignKey' => 'codigo_usuario'
        // ]);

        $this->hasOne('UsuariosDados', [
            'className' => 'UsuariosDados',
            'bindingKey' => 'codigo',
            'foreignKey' => 'codigo_usuario',
            'joinTable' => 'usuarios_dados',
            'propertyName' => 'dados',
        ]);

        $this->hasMany("PosSwtFormRespondido", [
            "className" => "PosSwtFormRespondido",
            "bindingKey" => "codigo",
            "foreignKey" => "codigo_usuario_inclusao",
            "joinTable" => "pos_swt_form_respondido",
            "propertyName" => "formularios_respondidos"
        ]);

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_usuario');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('apelido')
            ->maxLength('apelido', 256)
            ->requirePresence('apelido', 'create')
            ->notEmptyString('apelido');

        $validator
            ->scalar('senha')
            ->maxLength('senha', 172)
            ->allowEmptyString('senha');

        //comentado para tentativa de aprovacao na loja da apple
        // $validator
        //     ->email('email')
        //     ->allowEmptyString('email');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_uperfil')
            ->allowEmptyString('codigo_uperfil');

        $validator
            ->boolean('alerta_portal')
            ->notEmptyString('alerta_portal');

        $validator
            ->boolean('alerta_email')
            ->notEmptyString('alerta_email');

        $validator
            ->boolean('alerta_sms')
            ->notEmptyString('alerta_sms');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 12)
            ->allowEmptyString('celular');

        $validator
            ->scalar('token')
            ->maxLength('token', 172)
            ->allowEmptyString('token');

        $validator
            ->integer('fuso_horario')
            ->allowEmptyString('fuso_horario');

        $validator
            ->boolean('horario_verao')
            ->allowEmptyString('horario_verao');

        $validator
            ->integer('cracha')
            ->allowEmptyString('cracha');

        $validator
            ->dateTime('data_senha_expiracao')
            ->allowEmptyDateTime('data_senha_expiracao');

        $validator
            ->boolean('admin')
            ->allowEmptyString('admin');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_pai')
            ->allowEmptyString('codigo_usuario_pai');

        $validator
            ->allowEmptyString('restringe_base_cnpj');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->requirePresence('codigo_departamento', 'create')
            ->notEmptyString('codigo_departamento');

        $validator
            ->integer('codigo_filial')
            ->allowEmptyString('codigo_filial');

        $validator
            ->integer('codigo_proposta_credenciamento')
            ->allowEmptyString('codigo_proposta_credenciamento');

        $validator
            ->integer('codigo_fornecedor')
            ->allowEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->boolean('usuario_multi_empresa')
            ->allowEmptyString('usuario_multi_empresa');

        $validator
            ->integer('codigo_corretora')
            ->allowEmptyString('codigo_corretora');

        // $validator
        //     ->boolean('alerta_sm_usuario')
        //     ->allowEmptyString('alerta_sm_usuario');

        // $validator
        //     ->integer('codigo_gestor')
        //     ->allowEmptyString('codigo_gestor');

        return $validator;
    }

    /**
     * [getCodigoEmpresa metodo responsavel para pegar o codigo da empresa do usuario]
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getCodigoEmpresa($codigo_usuario)
    {

        $usuario = $this->find()->select(['codigo_empresa'])->where(['codigo' => $codigo_usuario])->first();
        $codigo_empresa = null;
        if (!empty($usuario)) {
            $codigo_empresa = $usuario->codigo_empresa;
        }

        return $codigo_empresa;
    }

    public function getUuariosByCliente(int $codigo_cliente)
    {
        if (empty($codigo_cliente)) {
            return ['error' => 'Código cliente requerido'];
        }

        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_funcao.codigo',
            'usuario_funcao.codigo_usuario',
            'usuario_funcao.codigo_funcao_tipo',
            'funcao_tipo.descricao',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            // 'senha' => 'Usuario.senha',
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

        $joins = [
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente',
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1',
            ],

            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'LEFT',
                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'LEFT',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'LEFT',
                'conditions' => 'FuncionarioSetorCargo.codigo = (SELECT TOP 1 _fsc.codigo
                                                                FROM funcionario_setores_cargos _fsc
                                                                     INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                                                                WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                                                                    AND _fsc.data_fim IS NULL
                                                                ORDER BY _fsc.codigo desc)',
            ],
            [
                'table' => 'usuario_funcao',
                'alias' => 'usuario_funcao',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = usuario_funcao.codigo_usuario ',
            ],
            [
                'table' => 'funcao_tipo',
                'alias' => 'funcao_tipo',
                'type' => 'LEFT',
                'conditions' => 'usuario_funcao.codigo_funcao_tipo = funcao_tipo.codigo',
            ],
        ];

        $group = array(
            'Usuario.codigo',
            'Usuario.nome',
            'Usuario.email',
            'UsuariosDados.data_nascimento',
            'UsuariosDados.celular',
            'UsuariosDados.telefone',
            'UsuariosDados.cpf',
            'UsuariosDados.sexo',
            'Usuario.senha',
            'cliente.codigo',
            'cliente.nome_fantasia',
            'cliente.razao_social',
            'contato_emergencia.nome',
            'contato_emergencia.telefone',
            'contato_emergencia.celular',
            'contato_emergencia.grau_parentesco',
            'contato_emergencia.email',
            'UsuarioSistema.codigo',
            'usuario_funcao.codigo',
            'usuario_funcao.codigo_usuario',
            'usuario_funcao.codigo_funcao_tipo',
            'funcao_tipo.descricao',
        );

        try {
            $query = $this->find()
                ->select($fields)
                ->join($joins)
                ->where(['Usuario.codigo_cliente' => $codigo_cliente])
                ->group($group);

            $dados = $query->all()->toArray();
            // debug($query->sql()); die;
        } catch (\Exception $th) {
            return ['error' => 'Erro na consulta a base de dados'];
        }

        return $dados;
    }

    /**
     * Obter dados de um usuario fornecendo $codigo_usuario(primary key)
     *
     * @param integer $codigo_usuario
     * @return array
     */
    public function obterDadosDoUsuarioAlocacao(int $codigo_usuario)
    {
        if (empty($codigo_usuario)) {
            return ['error' => 'Código usuário requerido'];
        }

        if (strlen($codigo_usuario) < 11) {
            //monta as conditions com codigo
            $conditions = ['Usuario.codigo' => $codigo_usuario];
        } else {
            //monta as conditions com cpf
            $conditions = ['UsuariosDados.cpf' => $codigo_usuario];
        }

        //campos do select
        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_funcao.codigo',
            'usuario_funcao.codigo_usuario',
            'usuario_funcao.codigo_funcao_tipo',
            'funcao_tipo.descricao',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            'codigo_perfil' => 'Usuario.codigo_uperfil',
            'notificacao' => "(CASE WHEN UsuarioSistema.codigo IS NOT NULL THEN 1 ELSE 0 END)",
            'cliente.codigo',
            'cliente.nome_fantasia',
            'cliente.razao_social',
            'cliente.flag_metodo_hazop',
            'cliente.flag_pda',
            'FuncionarioSetorCargo.codigo_cliente',
            'FuncionarioSetorCargo.codigo_setor',
            'FuncionarioSetorCargo.codigo_cargo',
            'avatar' => 'UsuariosDados.avatar',
            'codigo_gestor' => 'Usuario.codigo_gestor',
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
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            // [
            //     'table' => 'cliente',
            //     'alias' => 'cliente',
            //     'type' => 'LEFT',
            //     'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente',
            //     // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            // ],
            [
                'table' => 'cliente',
                'alias' => 'clienteMatriz',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = clienteMatriz.codigo OR clienteMatriz.codigo = UsuarioMultiCliente.codigo_cliente'
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1',
            ],

            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'LEFT',
                'conditions' => 'Funcionarios.cpf = Usuario.apelido OR Funcionarios.cpf = (CASE WHEN UsuariosDados.cpf IS NULL THEN Usuario.apelido ELSE Usuario.apelido END)',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'LEFT',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo AND ClienteFuncionario.ativo <> 0', //incluido o ativo <>0 para pegar somente as matriculas ativas CDCT-258
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'LEFT',
                'conditions' => 'FuncionarioSetorCargo.codigo = (SELECT TOP 1 _fsc.codigo
                                                                FROM funcionario_setores_cargos _fsc
                                                                     INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                                                                WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                                                                    AND _fsc.data_fim IS NULL
                                                                ORDER BY _fsc.codigo desc)',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao OR (Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente)'
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_funcao',
                'alias' => 'usuario_funcao',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = usuario_funcao.codigo_usuario ',
            ],
            [
                'table' => 'funcao_tipo',
                'alias' => 'funcao_tipo',
                'type' => 'LEFT',
                'conditions' => 'usuario_funcao.codigo_funcao_tipo = funcao_tipo.codigo',
            ],
            //            [
            //                'table' => 'cliente',
            //                'alias' => 'cliente',
            //                'type' => 'LEFT',
            //                'conditions' => 'cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao AND (Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente)'
            //                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            //            ],

        ];

        $group = array(
            'Usuario.codigo',
            'Usuario.nome',
            'Usuario.email',
            'Usuario.codigo_uperfil',
            'UsuariosDados.data_nascimento',
            'UsuariosDados.celular',
            'UsuariosDados.telefone',
            'UsuariosDados.cpf',
            'UsuariosDados.sexo',
            'Usuario.senha',
            'cliente.codigo',
            'cliente.nome_fantasia',
            'cliente.razao_social',
            'cliente.flag_metodo_hazop',
            'cliente.flag_pda',
            'FuncionarioSetorCargo.codigo_cliente',
            'FuncionarioSetorCargo.codigo_setor',
            'FuncionarioSetorCargo.codigo_cargo',
            'UsuariosDados.avatar',
            'Usuario.codigo_gestor',
            'contato_emergencia.nome',
            'contato_emergencia.telefone',
            'contato_emergencia.celular',
            'contato_emergencia.grau_parentesco',
            'contato_emergencia.email',
            'UsuarioSistema.codigo',
            'usuario_funcao.codigo',
            'usuario_funcao.codigo_usuario',
            'usuario_funcao.codigo_funcao_tipo',
            'funcao_tipo.descricao',
        );

        try {

            //executa os dados
            $dados = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->group($group)
                ->all()
                ->toArray();
            // debug($conditions);debug($dados->sql());exit;

        } catch (\Exception $th) {
            // Log::debug('[EXCEPTION]:'.$th->getMessage());

            $msg_erro = json_encode($th->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDoUsuarioAlocacao: ' . $msg_erro . ')'];
        }

        $usuario = (isset($dados[0]) ? $dados[0] : $dados);
        $iconv = new EncodingUtil();

        foreach ($dados as $key => $dado) {
            if (!empty($dado['cliente']['codigo'])) {
                $usuario['cliente'][] = array(
                    'codigo' => $dado['cliente']['codigo'],
                    'nome_fantasia' => $iconv->convert($dado['cliente']['nome_fantasia']),
                    'razao_social' => $iconv->convert($dado['cliente']['razao_social']),
                    'flag_metodo_hazop' => (int) $dado['cliente']['flag_metodo_hazop'],
                    'flag_pda' => (int) $dado['cliente']['flag_pda'],
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
        unset($usuario['cliente']['flag_metodo_hazop']);
        unset($usuario['cliente']['flag_pda']);
        unset($usuario['fornecedor']['codigo']);
        unset($usuario['fornecedor']['nome']);
        unset($usuario['fornecedor']['razao_social']);
        unset($usuario['fornecedor']['codigo_documento']);

        return $usuario;
    } //fim get_dados_usuario

    /**
     * Obter dados de um usuario fornecendo $codigo_usuario(primary key)
     *
     * @param integer $codigo_usuario
     * @return array
     */
    public function obterDadosDoUsuarioAlocacaoTherma(int $codigo_usuario)
    {
        if (empty($codigo_usuario)) {
            return ['error' => 'Código usuário requerido'];
        }

        if (strlen($codigo_usuario) < 11) {
            //monta as conditions com codigo
            $conditions = ['Usuario.codigo' => $codigo_usuario];
        }

        //campos do select
        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'celular' => 'Usuario.celular',
            'cpf' => 'Funcionarios.cpf',
            'sexo' => 'Funcionarios.sexo',
            'codigo_perfil' => 'Usuario.codigo_uperfil',
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
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            [
                'table' => 'cliente',
                'alias' => 'clienteMatriz',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = clienteMatriz.codigo OR clienteMatriz.codigo = UsuarioMultiCliente.codigo_cliente',
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1',
            ],

            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'LEFT',
                'conditions' => 'Funcionarios.cpf = Usuario.apelido',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'LEFT',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'LEFT',
                'conditions' => 'FuncionarioSetorCargo.codigo = (SELECT TOP 1 _fsc.codigo
                                                                FROM funcionario_setores_cargos _fsc
                                                                     INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                                                                WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                                                                    AND _fsc.data_fim IS NULL
                                                                ORDER BY _fsc.codigo desc)',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao AND (Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente)',
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],

        ];

        $group = array(
            'Usuario.codigo',
            'Usuario.nome',
            'Usuario.email',
            'Usuario.codigo_uperfil',
            'Funcionarios.data_nascimento',
            'Usuario.celular',
            'Funcionarios.cpf',
            'Funcionarios.sexo',
            'cliente.codigo',
            'cliente.nome_fantasia',
            'cliente.razao_social',
            'contato_emergencia.nome',
            'contato_emergencia.telefone',
            'contato_emergencia.celular',
            'contato_emergencia.grau_parentesco',
            'contato_emergencia.email',
            'UsuarioSistema.codigo',
        );

        try {

            //executa os dados
            $dados = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->group($group)
                ->all()
                ->toArray();
            // debug($conditions);debug($dados->sql());exit;

        } catch (\Exception $th) {
            // Log::debug('[EXCEPTION]:'.$th->getMessage());
            $msg_erro = json_encode($th->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDoUsuarioAlocacaoTherma:' . $msg_erro . ')'];
        }

        $usuario = (isset($dados[0]) ? $dados[0] : $dados);
        $iconv = new EncodingUtil();

        foreach ($dados as $key => $dado) {

            if (!empty($dado['cliente']['codigo'])) {
                $usuario['cliente'][] = array(
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
    } //fim get_dados_usuario_therma

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
            $conditions = ['Usuario.codigo' => $codigo_usuario];
        } else {
            //monta as conditions com cpf
            $conditions = ['UsuariosDados.cpf' => $codigo_usuario];
        }

        //campos do select
        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            'senha' => 'Usuario.senha',
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
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente',
                // 'conditions' => 'cliente.codigo = UsuarioMultiCliente.codigo_cliente'
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1',
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
            // debug($dados->sql());exit;
        } catch (\Exception $th) {
            // Log::debug('[EXCEPTION]:'.$th->getMessage());
            $msg_erro = json_encode($th->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDoUsuario:' . $msg_erro . ')'];
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
    } //fim get_dados_usuario matriz

    /**
     * Obter dados do funcionario caso exista relacionarmento
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_cliente
     * @return void
     */
    public function getUsuariosDadosFuncionario(int $codigo_usuario, int $codigo_cliente = null)
    {
        //campos do select
        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            'senha' => 'Usuario.senha',
            'codigo_funcionario' => 'Funcionarios.codigo',
        ];

        //monta os joins
        $joins = [
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf',
            ],
        ];

        $conditions['Usuario.codigo'] = $codigo_usuario;

        try {

            //executa a query
            $dados = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->first();
        } catch (\Exception $e) {
            $dados = ['error' => 'Erro na consulta'];
        }

        return $dados;
    } // fim getUsuariosDadosFuncionario

    /**
     * Obter codigo_cliente associado ao usuario
     *
     * @param integer $codigo_usuario
     * @return void
     */
    public function obterClientePorCodigoUsuario(int $codigo_usuario = null)
    {
        if (empty($codigo_usuario)) {
            return ['error' => 'Parâmetro código cliente não encontrado'];
        }

        $usuario_dados = $this->obterDadosDoUsuario($codigo_usuario);

        $usuario_dados_clientes = (array) $usuario_dados->cliente;

        if (
            empty($usuario_dados_clientes)
            || is_array($usuario_dados_clientes) && count($usuario_dados_clientes) == 0
        ) {
            return ['error' => 'Divergência no relacionamento, código cliente não encontrado'];
        }

        return $usuario_dados_clientes;
    }

    public function obterDadosDoUsuarioPorApelido(string $apelido, int $codigo_uperfil = null)
    {
        if (empty($apelido)) {
            return ['error' => 'Apelido requerido'];
        }

        if (strlen($apelido) < 256) {
            $conditions[] = "Usuario.apelido like '%" . $apelido . "%'";
        }

        if (!is_null($codigo_uperfil)) {
            $conditions["Usuario.codigo_uperfil"] = $codigo_uperfil;
        } else {
            //para nao pegar usuarios que são do lyn,thermal care ou gestao de riscos
            $conditions[] = "Usuario.codigo_uperfil NOT IN (9,42,43)";
        }

        $fields = ['email', 'nome', 'senha', 'codigo'];

        try {

            //executa a query
            $dados = $this->find()
                ->select($fields)
                ->where($conditions)
                ->first();
        } catch (\Exception $e) {
            $dados = ['error' => 'Erro na consulta'];
        }

        return $dados;
    }

    /**
     * Obter vinculo de funcionario de um usuário fornecendo codigo_usuario e codigo_cliente
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_cliente
     * @return integer|null
     */
    public function obterVinculoClienteFuncionario(int $codigo_usuario, int $codigo_cliente = null)
    {

        //pega os dados do usuario
        $usuario = $this->getUsuariosDadosFuncionario($codigo_usuario, $codigo_cliente);
        $codigo_funcionario = $usuario->codigo_funcionario;

        //pega o codigo_cliente_funcionario para os atestados
        $this->ClienteFuncionario = TableRegistry::get('ClienteFuncionario');
        //monta os joins
        $joins = [
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo = FuncionarioSetorCargo.codigo_cliente_funcionario',
            ],
        ];
        $cliente_funcionario = $this->ClienteFuncionario->find()
            ->join($joins)
            ->where([
                'FuncionarioSetorCargo.codigo_cliente_alocacao' => $codigo_cliente,
                'ClienteFuncionario.codigo_funcionario' => $codigo_funcionario,
            ])
            ->first();

        if (!empty($cliente_funcionario)) {
            return $cliente_funcionario;
        }

        return null;
    }

    /**
     * Obter código de relacionamento com cliente fornecido
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_cliente
     * @return integer|null
     */
    public function obterCodigoClienteFuncionario(int $codigo_usuario, int $codigo_cliente = null)
    {

        //pega os dados do usuario
        $cliente_funcionario = $this->obterVinculoClienteFuncionario($codigo_usuario, $codigo_cliente);

        if (!empty($cliente_funcionario)) {
            return $cliente_funcionario->codigo;
        }

        return null;
    }

    /**
     * Obter dados de um ou vários usuarios fornecendo $conditions
     *
     * @param array $conditions
     * @return Recordset|null
     */
    public function obterDadosDeUsuarios(array $conditions = [])
    {

        //campos do select
        $fields = [
            'codigo_usuario' => 'Usuario.codigo',
            'nome' => 'Usuario.nome',
            'email' => 'Usuario.email',
            'data_nascimento' => 'UsuariosDados.data_nascimento',
            'celular' => 'UsuariosDados.celular',
            'telefone' => 'UsuariosDados.telefone',
            'cpf' => 'UsuariosDados.cpf',
            'sexo' => 'UsuariosDados.sexo',
            'senha' => 'Usuario.senha',
            'notificacao' => "(CASE WHEN UsuarioSistema.codigo IS NOT NULL THEN 1 ELSE 0 END)",
            'tokenpush' => "UsuarioSistema.token_push",
            'platform' => "UsuarioSistema.platform",
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
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.codigo_usuario = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente',
            ],
            [
                'table' => 'usuario_contato_emergencia',
                'alias' => 'contato_emergencia',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1',
            ],
        ];

        try {

            //executa os dados
            $dados = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->all();
        } catch (\Exception $e) {
            // Log::debug('[EXCEPTION]:'.$e->getMessage());
            $msg_erro = json_encode($e->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDeUsuarios:' . $msg_erro . ')'];
        }

        return $dados;
    }

    /**
     * [getUsuarioFornecedorPermissoes metodo para trazer os usuarios dos fornecedores filtrados e suas permissoes]
     * @param  [type] $codigos_fornecedores [description]
     * @return [type]                       [description]
     */
    public function getUsuarioFornecedorPermissoes($codigos_fornecedores, $codigo_usuario = null)
    {

        //query para pegar a listagem
        $fields = array(
            'codigo_usuario' => 'Usuario.codigo',
            'codigo_medico' => 'Usuario.codigo_medico',
            'nome' => 'RHHealth.dbo.ufn_decode_utf8_string(Usuario.nome)',
            'nome_usuario' => 'RHHealth.dbo.ufn_decode_utf8_string(Usuario.apelido)',
            'email' => 'Usuario.email',
            'codigo_perfil' => 'Uperfil.codigo',
            'perfil' => 'RHHealth.dbo.ufn_decode_utf8_string(Uperfil.descricao)',
            'codigo_fornecedor_principal' => 'Usuario.codigo_fornecedor',
            'codigo_fornecedor' => 'Fornecedor.codigo',
            'nome_fornecedor' => 'RHHealth.dbo.ufn_decode_utf8_string(Fornecedor.nome)',
            'codigo_permissao' => 'FornecedorPermissoes.codigo',
            'descricao_permissoes' => 'FornecedorPermissoes.descricao',
        );

        //joins
        $joins = [
            [
                'table' => 'uperfis',
                'alias' => 'Uperfil',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo_uperfil = Uperfil.codigo',
            ],
            [
                'table' => 'usuario_multi_fornecedor',
                'alias' => 'UsuarioMultiFornecedor',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuarioMultiFornecedor.codigo_usuario',
            ],
            [
                'table' => 'fornecedores',
                'alias' => 'Fornecedor',
                'type' => 'INNER',
                'conditions' => 'UsuarioMultiFornecedor.codigo_fornecedor = Fornecedor.codigo',
            ],
            [
                'table' => 'usuario_fornecedor_permissoes',
                'alias' => 'UsuarioFornecedorPermissoes',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuarioFornecedorPermissoes.codigo_usuario',
            ],
            [
                'table' => 'fornecedor_permissoes',
                'alias' => 'FornecedorPermissoes',
                'type' => 'INNER',
                'conditions' => 'UsuarioFornecedorPermissoes.codigo_fornecedor_permissoes = FornecedorPermissoes.codigo',
            ],
        ];

        $conditions[] = "UsuarioMultiFornecedor.codigo_fornecedor IN ({$codigos_fornecedores})";

        if (!is_null($codigo_usuario)) {
            $conditions[] = "Usuario.codigo = " . $codigo_usuario;
        }

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        // debug($dados->sql());exit;

        $dados_usuario = array();
        foreach ($dados as $key => $d) {
            $dados_usuario[$d['codigo_usuario']]['codigo_usuario'] = $d['codigo_usuario'];
            $dados_usuario[$d['codigo_usuario']]['nome'] = $d['nome'];
            $dados_usuario[$d['codigo_usuario']]['nome_usuario'] = $d['nome_usuario'];
            $dados_usuario[$d['codigo_usuario']]['email'] = $d['email'];
            $dados_usuario[$d['codigo_usuario']]['codigo_funcao'] = $d['codigo_perfil'];
            $dados_usuario[$d['codigo_usuario']]['funcao'] = $d['perfil'];
            $dados_usuario[$d['codigo_usuario']]['codigo_fornecedor'] = $d['codigo_fornecedor_principal'];
            $dados_usuario[$d['codigo_usuario']]['codigo_medico'] = $d['codigo_medico'];

            $dados_usuario[$d['codigo_usuario']]['Fornecedor'][$d['codigo_fornecedor']]['codigo_fornecedor'] = $d['codigo_fornecedor'];
            $dados_usuario[$d['codigo_usuario']]['Fornecedor'][$d['codigo_fornecedor']]['nome_fornecedor'] = $d['nome_fornecedor'];

            $dados_usuario[$d['codigo_usuario']]['Permissao'][$d['codigo_permissao']]['codigo_permissao'] = $d['codigo_permissao'];
            $dados_usuario[$d['codigo_usuario']]['Permissao'][$d['codigo_permissao']]['descricao_permissoes'] = $d['descricao_permissoes'];
        }
        //debug($dados_usuario);exit;

        return $dados_usuario;
    } //fim getUsuarioFornecedorPermissoes

    /**
     * [getUsuarioPermissoes pega as permissoes dos usuarios]
     * @param  int    $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getUsuarioPerfilPermissao(int $codigo_usuario)
    {
        //campos para retornar
        $fields = array(
            'codigo' => 'Objeto.id',
        );

        //monta os joins
        $joins = [
            [
                'table' => 'uperfis',
                'alias' => 'Uperfil',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo_uperfil = Uperfil.codigo',
            ],
            [
                'table' => 'objetos_acl_tipos_perfis',
                'alias' => 'Oatp',
                'type' => 'INNER',
                'conditions' => 'Uperfil.codigo_tipo_perfil = Oatp.codigo_tipo_perfil',
            ],
            [
                'table' => 'objetos_acl',
                'alias' => 'Objeto',
                'type' => 'INNER',
                'conditions' => 'Oatp.objeto_id = Objeto.id',
            ],
        ];

        //pega pelo perfil qual é o
        $dados_perfil = $this->find()->select($fields)->join($joins)->where(['Usuario.codigo' => $codigo_usuario])->hydrate(false)->all()->toArray();
        // debug($dados_perfil);
        //array de dados liberado
        /*
        '2213' => 'Audiometria',
        '1156' => 'Ficha Clinica',
        '2342' => 'Ficha Psicossocial'

        '1170' => 'Baixa de Pedidos de Exame',

        '2197' => 'EmissÃ£o de Pedidos',
        '1182' => 'Atestados MÃ©dicos',
        '2267' => 'Laudo Caracterizador de DeficiÃªncia',
        '2281' => 'Exames Baixados',
        '2287' => 'Laudo Caracterizador de DeficiÃªncia',
        '2294' => 'FuncionÃ¡rio Faturamento',
         */

        //verifica se tem os codigos para acessar os exames configurados
        $acesso_liberado = array(
            'audiometria' => false,
            'ficha_clinica' => false,
            'psicossocial' => false,
            'baixa_pedido' => false,
            'emissao_pedido' => false,
            'atestado_medico' => false,
            'pcd' => false,
            'exames_baixados' => false,
            'funcionario_faturamento' => false,
        );
        //varre o perfil
        foreach ($dados_perfil as $codigo_perfil) {

            // debug($codigo_perfil);

            //verifica se tem os codigos
            switch ($codigo_perfil['codigo']) {
                case '2213': // 'Audiometria',
                    $acesso_liberado['audiometria'] = true;
                    break;
                case '1156': // 'Ficha Clinica',
                    $acesso_liberado['ficha_clinica'] = true;
                    break;
                case '2342': // 'Ficha Psicossocial'
                    $acesso_liberado['psicossocial'] = true;
                    break;
                case '1170': // 'Baixa de Pedidos de Exame',
                    $acesso_liberado['baixa_pedido'] = true;
                    break;
                case '2197': // 'EmissÃ£o de Pedidos',
                    $acesso_liberado['emissao_pedido'] = true;
                    break;
                case '1182': // 'Atestados MÃ©dicos',
                    $acesso_liberado['atestado_medico'] = true;
                    break;
                case '2267': // 'Laudo Caracterizador de DeficiÃªncia',
                case '2287': // 'Laudo Caracterizador de DeficiÃªncia',
                    $acesso_liberado['pcd'] = true;
                    break;
                case '2281': // 'Exames Baixados',
                    $acesso_liberado['exames_baixados'] = true;
                    break;
                case '2294': // 'FuncionÃ¡rio Faturamento',
                    $acesso_liberado['funcionario_faturamento'] = true;
                    break;
            } //fim switch
        }

        return $acesso_liberado;
    } //fim getUsuarioPermissoes

    public function getDadosUsuario($codigo_usuario)
    {
        //query para pegar a listagem
        $fields = array(
            'codigo_usuario' => 'Usuario.codigo',
            'nome_usuario' => 'RHHealth.dbo.ufn_decode_utf8_string(Usuario.nome)',
            'funcao' => 'RHHealth.dbo.ufn_decode_utf8_string(Uperfil.descricao)',
            'codigo_fornecedor' => 'Usuario.codigo_fornecedor',
            'codigo_medico' => 'Usuario.codigo_medico',
        );

        //joins
        $joins = [
            [
                'table' => 'uperfis',
                'alias' => 'Uperfil',
                'type' => 'LEFT',
                'conditions' => ' Usuario.codigo_uperfil = Uperfil.codigo ',
            ],
        ];

        $conditions[] = " Usuario.codigo = " . $codigo_usuario . " ";

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->first();

        return $dados;
    }

    public function getAllFornecedoresUsuario($codigo_usuario)
    {
        $query = "select
                     (case when f.codigo is not null and f.codigo = fCliente.codigo then f.codigo
                     when f.codigo is not null and f.codigo = umf.codigo_fornecedor then f.codigo
                     when fCliente.codigo is not null and fCliente.codigo = umf.codigo_fornecedor then fCLiente.codigo
                     when umf.codigo_fornecedor is not null then umf.codigo_fornecedor
                     when fCliente.codigo is not NULL then fCliente.codigo
                     else f.codigo end) as codigo_fornecedor,
                     RHHealth.dbo.ufn_decode_utf8_string(f.nome) as nome_fornecedor
                    from usuario u
                     left join fornecedores fCliente on u.codigo_cliente = fCliente.ambulatorio_codigo_cliente
                     left join usuario_multi_fornecedor umf on u.codigo = umf.codigo_usuario
                     left join fornecedores f on u.codigo_fornecedor = f.codigo or umf.codigo_fornecedor = f.codigo or fCliente.codigo = f.codigo
                     left join usuario_multi_cliente umc on umc.codigo_usuario = u.codigo and umc.codigo_cliente = fCliente.ambulatorio_codigo_cliente
                    where 1=1
                     and u.codigo = " . $codigo_usuario . "
                    group by
                    u.nome,
                     u.codigo_fornecedor,
                     u.codigo_cliente,
                     fCliente.codigo,
                     f.codigo,
                     f.nome,
                     umf.codigo_fornecedor";

        //Retorna os dados da consulta ao banco
        $result = $this->connection->execute($query)->fetchAll('assoc');

        $qtd = 0;
        foreach ($result as $row) {
            if (empty($row['codigo_fornecedor']) or is_null($row['codigo_fornecedor'])) {
                $qtd++;
            }
        }

        if ($qtd <= 0) {
            return $result;
        } else {
            return [];
        }
    }

    public function getFornecedorPermissaoByUsuario($codigo_usuario)
    {
        $query = "select fp.codigo, fp.descricao from usuario_fornecedor_permissoes ufp
                  inner join fornecedor_permissoes fp on  ufp.codigo_usuario = " . $codigo_usuario . " and ufp.codigo_fornecedor_permissoes = fp.codigo";

        //Retorna os dados da consulta ao banco
        $result = $this->connection->execute($query)->fetchAll('assoc');

        return $result;
    }

    /**
     * Valida se existe algum vinculo com qualquer cliente
     *
     * true : existe ou falso não existe
     *
     * @param integer $codigo_usuario
     * @return bool
     */
    public function validaSeUsuarioPossuiVinculoCliente(int $codigo_usuario = null)
    {
        return boolval(!empty($this->obterVinculosMultiCliente()));
    }

    /**
     * obtem informação de vinculo por codigo_usuario
     *
     * @param integer $codigo_usuario
     * @return void
     */
    private function obterVinculosMultiCliente(int $codigo_usuario = null)
    {

        //carrega a usuario_multi_cliente
        $this->UsuarioMultiCliente = TableRegistry::get('UsuarioMultiCliente');

        //pega os dados do usuariomulticliente
        $usuarioMultiCliente = $this->UsuarioMultiCliente
            ->find()
            ->where(['codigo_usuario' => $codigo_usuario])->all();

        //verifica se existe o usuario multi cliente
        if (!empty($usuarioMultiCliente)) {
            return $usuarioMultiCliente;
        }

        return null;
    }

    /**
     * [getClienteGrupoEconomico pega os dados do grupo economico da unidade que esta passando]
     * @param  [type] $codigo_cliente [description]
     * @return [type]                 [description]
     */
    public function getClienteGrupoEconomico($codigo_cliente)
    {
        //carrega a usuario_multi_cliente
        $this->GrupoEconomico = TableRegistry::get('GruposEconomicos');

        //monta os joins
        $joins = [
            [
                'table' => 'grupos_economicos_clientes',
                'alias' => 'GrupoEconomicoCliente',
                'type' => 'INNER',
                'conditions' => 'GruposEconomicos.codigo = GrupoEconomicoCliente.codigo_grupo_economico',
            ],
        ];

        $where = ['GrupoEconomicoCliente.codigo_cliente' => $codigo_cliente];

        $dados = $this->GrupoEconomico->find()->join($joins)->where($where)->first();

        return $dados;
    } //finm getClienteGrupoEocnomico

    /**
     * Obter dados de um usuario fornecendo $codigo_usuario(primary key)
     *
     * @param integer $codigo_usuario
     * @return array
     */
    public function usuarioClientes(int $codigo_usuario)
    {
        if (empty($codigo_usuario)) {
            return ['error' => 'Código usuário requerido'];
        }

        $conditions = ['Usuario.codigo' => $codigo_usuario];

        //campos do select
        $fields = [
            'codigo' => 'cliente.codigo',
            'nome_fantasia' => 'RHHealth.dbo.ufn_decode_utf8_string(cliente.nome_fantasia)',
            'razao_social' => 'cliente.razao_social',
        ];

        //monta os joins
        $joins = [
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario',
            ],
            [
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioMultiCliente.codigo_usuario',
            ],
            [
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario',
            ],
            [
                'table' => 'cliente',
                'alias' => 'cliente',
                'type' => 'LEFT',
                'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente',
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
            // debug($dados->sql());exit;
        } catch (\Exception $th) {
            // Log::debug('[EXCEPTION]:'.$th->getMessage());
            $msg_erro = json_encode($th->getMessage());
            return ['error' => 'Erro na consulta a base de dados (obterDadosDoUsuario:' . $msg_erro . ')'];
        }

        return array_values(array_unique($dados));
    } //fim get_dados_usuario matriz

    public function getEmployeesUserClients($userCode, $internal = null, $clientUserCode = null, $userPermission = null)
    { //
        $fields = [
            'codigo' => 'Usuario.codigo',
            'nome' => 'Usuario.nome',
            'avatar' => 'UsuariosDados.avatar',
            'interno' => 'Usuario.interno',
        ];

        $this->UsuarioMultiCliente = TableRegistry::get('UsuarioMultiCliente');

        $multiclientes_usuario = $this->UsuarioMultiCliente->find()
            ->select(['codigo_cliente'])
            ->where(['codigo_usuario' => $userCode])
            ->all();

        if (!empty($multiclientes_usuario)) {
            $arr_data = [];
            foreach ($multiclientes_usuario as $row) {
                $arr_data[] = $row['codigo_cliente'];
            }

            if (count($arr_data))
                $clientUserCode = implode(",", $arr_data);
        }

        // debug($clientUserCode);
        // exit;

        // $units = $this->pegarUnidadesDoUsuario($userCode);//pega as unidades do usuario

        // if (!empty($units)) {//se existe unidades
        //     $clientUserCode = $units;
        // }

        $joinsDefault = [

            [
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => "Cliente.codigo IN ({$clientUserCode})",
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
            ],
            [ //Foi adicionado um CASA nessa consulta para verificar se o campo Usuario.apelido tem os 11 characteres de um CPF, se não houver, concatena o 0 na frente
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = (CASE
                                                        WHEN UsuariosDados.cpf IS NULL THEN (
                                                        case 
                                                            when LEN(Usuario.apelido) < 11 
                                                            then CONCAT(0, Usuario.apelido)
                                                            else Usuario.apelido
                                                            end
                                                        )
                                                        ELSE UsuariosDados.cpf
                                                    END)',
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => "ClienteFuncionario.codigo_funcionario = Funcionarios.codigo",
                // 'conditions' => "ClienteFuncionario.codigo_funcionario = Funcionarios.codigo AND ClienteFuncionario.codigo_cliente IN ({$clientUserCode})",
            ],
        ];

        $conditions = [];

        if (!is_null($internal)) {
            $conditions['Usuario.interno'] = $internal; //seleciona os usuarios internos
        }

        if (!is_null($userPermission)) {
            $conditions['Acoes.codigo'] = $userPermission; //$userPermission;

            $additionalJoins = [
                [
                    'table' => 'usuario_subperfil',
                    'alias' => 'UsuarioSubperfil',
                    'type' => 'INNER',
                    'conditions' => 'UsuarioSubperfil.codigo_usuario = Usuario.codigo',
                ],
                [
                    'table' => 'subperfil_acoes',
                    'alias' => 'SubperfilAcoes',
                    'type' => 'INNER',
                    'conditions' => 'SubperfilAcoes.codigo_subperfil = UsuarioSubperfil.codigo_subperfil',
                ],
                [
                    'table' => 'acoes',
                    'alias' => 'Acoes',
                    'type' => 'INNER',
                    'conditions' => 'Acoes.codigo = SubperfilAcoes.codigo_acao',
                ],
            ];

            $joinsDefault = array_merge($joinsDefault, $additionalJoins); //adiciona os joins adicionais
        }

        $conditions['Usuario.ativo'] = 1; //somente usuários ativos
        $conditions['Usuario.codigo_uperfil'] = 50; //perfil de funcionario

        $conditions[] = "Usuario.codigo_cliente IN ({$clientUserCode})"; //filtra os usuarios de acordo com as unidades do usuario logado

        try {

            $data = $this->find() //busca os dados
                ->select($fields)
                ->join($joinsDefault)
                ->where($conditions)
                ->group(['Usuario.codigo', 'Usuario.nome', 'UsuariosDados.avatar', 'Usuario.interno'])
                ->orderAsc('Usuario.nome');
            // ->all()
            // ->toArray()
            // ->sql();
            // debug($data);exit;

            return $data; //retorna os dados
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function pegarUnidadesDoUsuario($codigo_usuario)
    {
        $fields = array(
            'codigo' => 'Cliente.codigo'
        );

        $joins = array(
            array(
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'INNER',
                'conditions' => 'UsuarioMultiCliente.codigo_usuario = Usuario.codigo',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = UsuarioMultiCliente.codigo_cliente',
            )
        );

        $conditions = array(
            'Usuario.codigo' => $codigo_usuario
        );

        $data = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->enableHydration(false)
            ->all()
            ->toArray();

        $arr_data = array();
        $cliente = "";

        if (!empty($data)) {
            foreach ($data as $row) {
                $arr_data[] = $row['codigo'];
            }

            $cliente = implode(",", $arr_data);
        }
        return $cliente;
    }

    /**
     * Obter matricula e cliente do gestor do usuário pelo código do mesmo
     *
     * @param integer $userCode
     * @return array
     */
    public function getManagerByUserId(int $userCode = null)
    {
        try {
            if (is_null($userCode)) {
                return null;
            }

            $fields = [
                'codigo_cliente' => 'ClienteFuncionario.codigo_cliente_chefia_imediata',
                'matricula' => 'ClienteFuncionario.matricula_chefia_imediata',
            ];

            $joins = [
                [
                    'table' => 'usuarios_dados',
                    'alias' => 'UsuariosDados',
                    'type' => 'INNER',
                    'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
                ],
                [
                    'table' => 'funcionarios',
                    'alias' => 'Funcionarios',
                    'type' => 'INNER',
                    'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                ],
                [
                    'table' => 'cliente_funcionario',
                    'alias' => 'ClienteFuncionario',
                    'type' => 'INNER',
                    'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                ],
            ];

            $conditions = [
                "Usuario.codigo = $userCode",
                'ClienteFuncionario.matricula_chefia_imediata IS NOT NULL',
                'ClienteFuncionario.codigo_cliente_chefia_imediata IS NOT NULL',
                'ClienteFuncionario.data_demissao IS NULL',
                'ClienteFuncionario.ativo = 1'
            ];

            $registry = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->hydrate(false)
                ->first();

            return $registry;
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Obter dados do funcionarios pela matricula e código do cliente
     *
     * @param string $registration
     * @param int $clientCode
     * @return array
     */
    public function getEmployee(string $registration = null, int $clientCode = null, bool $pushNotification = false)
    {
        try {
            if (is_null($registration) || is_null($clientCode)) {
                return null;
            }

            $fields = [];
            $joins = [];
            $conditions = [];

            if ($pushNotification) {
                $fields = [
                    'codigo_cliente_chefia_imediata' => 'ClienteFuncionario.codigo_cliente_chefia_imediata',
                    'matricula_chefia_imediata' => 'ClienteFuncionario.matricula_chefia_imediata',
                    'matricula' => 'ClienteFuncionario.matricula',
                    'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                    'codigo_usuario' => 'Usuario.codigo',
                    'telefone' => 'UsuarioSistema.celular',
                    'token' => 'UsuarioSistema.token_push',
                    'plataforma' => 'UsuarioSistema.platform'
                ];

                $joins = [
                    [
                        'table' => 'usuarios_dados',
                        'alias' => 'UsuariosDados',
                        'type' => 'INNER',
                        'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
                    ],
                    [
                        'table' => 'funcionarios',
                        'alias' => 'Funcionarios',
                        'type' => 'INNER',
                        'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                    ],
                    [
                        'table' => 'cliente_funcionario',
                        'alias' => 'ClienteFuncionario',
                        'type' => 'INNER',
                        'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                    ],
                    [
                        'table' => 'usuario_sistema',
                        'alias' => 'UsuarioSistema',
                        'type' => 'INNER',
                        'conditions' => 'UsuarioSistema.codigo_usuario = Usuario.codigo',
                    ],
                ];

                $conditions = [
                    "ClienteFuncionario.matricula = '$registration'",
                    "ClienteFuncionario.codigo_cliente = $clientCode",
                    'ClienteFuncionario.data_demissao IS NULL',
                    'ClienteFuncionario.ativo = 1',
                    'UsuarioSistema.token_push IS NOT NULL',
                    'UsuarioSistema.platform IS NOT NULL',
                    'UsuarioSistema.ativo = 1',
                    'UsuarioSistema.codigo_sistema = 8',
                ];
            } else {
                $fields = [
                    'codigo_cliente_chefia_imediata' => 'ClienteFuncionario.codigo_cliente_chefia_imediata',
                    'matricula_chefia_imediata' => 'ClienteFuncionario.matricula_chefia_imediata',
                    'matricula' => 'ClienteFuncionario.matricula',
                    'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                    'email' => 'Usuario.email',
                    'codigo_usuario' => 'Usuario.codigo',
                ];

                $joins = [
                    [
                        'table' => 'usuarios_dados',
                        'alias' => 'UsuariosDados',
                        'type' => 'INNER',
                        'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
                    ],
                    [
                        'table' => 'funcionarios',
                        'alias' => 'Funcionarios',
                        'type' => 'INNER',
                        'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                    ],
                    [
                        'table' => 'cliente_funcionario',
                        'alias' => 'ClienteFuncionario',
                        'type' => 'INNER',
                        'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                    ],
                ];

                $conditions = [
                    "ClienteFuncionario.matricula = '$registration'",
                    "ClienteFuncionario.codigo_cliente = $clientCode",
                    'ClienteFuncionario.data_demissao IS NULL',
                    'ClienteFuncionario.ativo = 1',
                    'Usuario.email IS NOT NULL',
                ];
            }

            $registry = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->hydrate(false)
                ->first();

            return $registry;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function getUserToPushNotification(int $userCode = null)
    {
        try {
            if (is_null($userCode)) {
                return null;
            }

            $fields = [
                'codigo_usuario' => 'Usuario.codigo',
                'telefone' => 'UsuarioSistema.celular',
                'token' => 'UsuarioSistema.token_push',
                'plataforma' => 'UsuarioSistema.platform',
            ];

            $joins = [
                [
                    'table' => 'usuario_sistema',
                    'alias' => 'UsuarioSistema',
                    'type' => 'INNER',
                    'conditions' => 'UsuarioSistema.codigo_usuario = Usuario.codigo',
                ],
            ];

            $conditions = [
                'UsuarioSistema.token_push IS NOT NULL',
                'UsuarioSistema.platform IS NOT NULL',
                'UsuarioSistema.ativo = 1',
                'UsuarioSistema.codigo_sistema = 8',
                "Usuario.codigo = $userCode",
            ];

            $registry = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->hydrate(false)
                ->first();

            return $registry;
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function getUsersByGoal(array $goal)
    {
        $fields = array(
            "Usuario.codigo",
            "Usuario.email",
        );

        $joins = array(
            array(
                "table" => "funcionarios",
                "alias" => "Funcionarios",
                "type" => "INNER",
                "conditions" => array(
                    "Funcionarios.cpf = Usuario.apelido"
                )
            ),
            array(
                "table" => "cliente_funcionario",
                "alias" => "ClienteFuncionario",
                "type" => "INNER",
                "conditions" => array(
                    "ClienteFuncionario.codigo_funcionario = Funcionarios.codigo"
                )
            ),
            array(
                "table" => "funcionario_setores_cargos",
                "alias" => "FuncionarioSetoresCargos",
                "type" => "INNER",
                "conditions" => array(
                    "FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo",
                    "FuncionarioSetoresCargos.codigo_cliente = ClienteFuncionario.codigo_cliente",
                    "FuncionarioSetoresCargos.data_fim IS NULL"
                )
            )
        );

        $conditions = array(
            "FuncionarioSetoresCargos.codigo_cliente_alocacao = {$goal['codigo_cliente']}",
            "FuncionarioSetoresCargos.codigo_setor = {$goal['codigo_setor']}",
            "Usuario.codigo_uperfil = 50"
        );

        if (!empty($goal["codigo_cliente_bu"]) && !empty($goal["codigo_cliente_opco"])) {
            $conditions["ClienteFuncionario.codigo_cliente_bu"] = $goal["codigo_cliente_bu"];
            $conditions["ClienteFuncionario.codigo_cliente_opco"] = $goal["codigo_cliente_opco"];
        }

        $data = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->contain([
                "PosSwtFormRespondido" => [
                    "queryBuilder" => function ($query) {
                        return $query->select([
                            "codigo_usuario_inclusao",
                            "registros_criados" => "COUNT(codigo_usuario_inclusao)",
                            "data_registro" => "FORMAT(data_inclusao, 'yyyy-MM-dd')"
                        ])
                            ->where([
                                "codigo_form_respondido_swt IS NULL",
                                "data_inclusao >= DATEADD(MONTH, -13, GETDATE())"
                            ])
                            ->group([
                                "codigo_usuario_inclusao",
                                "FORMAT(data_inclusao, 'yyyy-MM-dd')"
                            ])
                            ->order([
                                "data_registro DESC"
                            ]);
                    }
                ]
            ])
            ->enableHydration(false)
            ->all()
            ->toArray();

        return $data;
    }

    public function obterUnidadesUsuarioLogado($codigo_usuario)
    {
        $fields = array(
            'codigo_unidade' => 'ISNULL(Cliente.codigo, Usuario.codigo_cliente)'
        );

        $joins = array(
            array(
                'table' => 'usuario_multi_cliente',
                'alias' => 'UsuarioMultiCliente',
                'type' => 'LEFT',
                'conditions' => 'UsuarioMultiCliente.codigo_usuario = Usuario.codigo',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'LEFT',
                'conditions' => 'Cliente.codigo = UsuarioMultiCliente.codigo_cliente',
            )
        );

        $conditions = array(
            'Usuario.codigo' => $codigo_usuario
        );

        $data = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);

        // debug($conditions); 
        // debug($data->sql()); die;

        $arr_data = array();
        $cliente = "";

        if (!empty($data)) {
            foreach ($data as $row) {
                $arr_data[] = $row['codigo_unidade'];
            }

            $cliente = implode(",", $arr_data);
        }
        return $cliente;
    }

    /**
     * Obter localidade/alocação de um dado usuário
     * @param int $codigo_usuario
     * @return array
     */
    public function obterLocalidade($codigo)
    {

        $localidadeQuery = $this->find()
            ->select([
                'Cliente.razao_social',
                'Cliente.codigo',
                'Cliente.nome_fantasia',
                'ClienteEndereco.logradouro',
                'ClienteEndereco.numero',
                'ClienteEndereco.complemento',
                'ClienteEndereco.bairro',
                'ClienteEndereco.cep',
                'ClienteEndereco.cidade',
                'ClienteEndereco.estado_abreviacao',
            ])
            ->join(
                [
                    [
                        'table' => 'funcionarios',
                        'alias' => 'Funcionarios',
                        'type' => 'INNER',
                        'conditions' => 'Funcionarios.cpf = Usuario.apelido'
                    ],
                    [
                        'table' => 'cliente_funcionario',
                        'alias' => 'ClienteFuncionario',
                        'type' => 'INNER',
                        'conditions' => [
                            'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo',
                            'ClienteFuncionario.ativo = 1'
                        ]
                    ],
                    [
                        'table' => 'funcionario_setores_cargos',
                        'alias' => 'FuncionarioSetoresCargos',
                        'type' => 'INNER',
                        'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo'
                    ],
                    [
                        'table' => 'cliente',
                        'alias' => 'Cliente',
                        'type' => 'INNER',
                        'conditions' => 'Cliente.codigo = FuncionarioSetoresCargos.codigo_cliente'
                    ],
                    [
                        'table' => 'cliente_endereco',
                        'alias' => 'ClienteEndereco',
                        'type' => 'INNER',
                        'conditions' => 'ClienteEndereco.codigo_cliente = Cliente.codigo'
                    ]
                ]
            )
            ->where([
                'Usuario.codigo' => $codigo,
                "FuncionarioSetoresCargos.data_fim IS NULL OR FuncionarioSetoresCargos.data_fim = ''"
            ]);


        return $localidadeQuery
            ->enableHydration()
            ->first()
            ->toArray();
    }
}
