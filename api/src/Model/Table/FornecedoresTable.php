<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * Fornecedores Model
 *
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 * @property \App\Model\Table\HorarioTable&\Cake\ORM\Association\BelongsToMany $Horario
 * @property \App\Model\Table\MedicosTable&\Cake\ORM\Association\BelongsToMany $Medicos
 *
 * @method \App\Model\Entity\Fornecedore get($primaryKey, $options = [])
 * @method \App\Model\Entity\Fornecedore newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Fornecedore[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Fornecedore|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fornecedore saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Fornecedore patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Fornecedore[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Fornecedore findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('fornecedores');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Endereco', [
            'foreignKey' => 'fornecedore_id',
            'targetForeignKey' => 'endereco_id',
            'joinTable' => 'fornecedores_endereco'
        ]);
        $this->belongsToMany('Horario', [
            'foreignKey' => 'fornecedore_id',
            'targetForeignKey' => 'horario_id',
            'joinTable' => 'fornecedores_horario'
        ]);
        $this->belongsToMany('Medicos', [
            'foreignKey' => 'fornecedore_id',
            'targetForeignKey' => 'medico_id',
            'joinTable' => 'fornecedores_medicos'
        ]);

        $this->addBehavior('SincronizarCodigoDocumento');
        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_fornecedor');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_inclusao' => 'new',
                    'data_alteracao' => 'always',
                ]
            ]
        ]);
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
            ->scalar('codigo_documento')
            ->maxLength('codigo_documento', 14)
            ->allowEmptyString('codigo_documento');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->allowEmptyString('razao_social');

        $validator
            ->scalar('responsavel_administrativo')
            ->maxLength('responsavel_administrativo', 255)
            ->allowEmptyString('responsavel_administrativo');

        $validator
            ->integer('tipo_atendimento')
            ->allowEmptyString('tipo_atendimento');

        $validator
            ->integer('acesso_portal')
            ->allowEmptyString('acesso_portal');

        $validator
            ->integer('exames_local_unico')
            ->allowEmptyString('exames_local_unico');

        $validator
            ->scalar('numero_banco')
            ->maxLength('numero_banco', 255)
            ->allowEmptyString('numero_banco');

        $validator
            ->scalar('tipo_conta')
            ->maxLength('tipo_conta', 255)
            ->allowEmptyString('tipo_conta');

        $validator
            ->scalar('favorecido')
            ->maxLength('favorecido', 255)
            ->allowEmptyString('favorecido');

        $validator
            ->scalar('agencia')
            ->maxLength('agencia', 255)
            ->allowEmptyString('agencia');

        $validator
            ->scalar('numero_conta')
            ->maxLength('numero_conta', 255)
            ->allowEmptyString('numero_conta');

        $validator
            ->integer('interno')
            ->allowEmptyString('interno');

        $validator
            ->scalar('atendente')
            ->maxLength('atendente', 255)
            ->allowEmptyString('atendente');

        $validator
            ->date('data_contratacao')
            ->allowEmptyDate('data_contratacao');

        $validator
            ->date('data_cancelamento')
            ->allowEmptyDate('data_cancelamento');

        $validator
            ->integer('contrato_ativo')
            ->allowEmptyString('contrato_ativo');

        $validator
            ->integer('codigo_soc')
            ->allowEmptyString('codigo_soc');

        $validator
            ->integer('dia_do_pagamento')
            ->allowEmptyString('dia_do_pagamento');

        $validator
            ->integer('disponivel_para_todas_as_empresas')
            ->allowEmptyString('disponivel_para_todas_as_empresas');

        $validator
            ->scalar('especialidades')
            ->maxLength('especialidades', 255)
            ->allowEmptyString('especialidades');

        $validator
            ->scalar('tipo_de_pagamento')
            ->maxLength('tipo_de_pagamento', 255)
            ->allowEmptyString('tipo_de_pagamento');

        $validator
            ->scalar('texto_livre')
            ->allowEmptyString('texto_livre');

        $validator
            ->allowEmptyString('codigo_status_contrato_fornecedor');

        $validator
            ->scalar('responsavel_tecnico')
            ->maxLength('responsavel_tecnico', 255)
            ->allowEmptyString('responsavel_tecnico');

        $validator
            ->integer('codigo_conselho_profissional')
            ->allowEmptyString('codigo_conselho_profissional');

        $validator
            ->scalar('responsavel_tecnico_conselho_numero')
            ->maxLength('responsavel_tecnico_conselho_numero', 25)
            ->allowEmptyString('responsavel_tecnico_conselho_numero');

        $validator
            ->scalar('responsavel_tecnico_conselho_uf')
            ->maxLength('responsavel_tecnico_conselho_uf', 2)
            ->allowEmptyString('responsavel_tecnico_conselho_uf');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->boolean('utiliza_sistema_agendamento')
            ->allowEmptyString('utiliza_sistema_agendamento');

        $validator
            ->scalar('tipo_unidade')
            ->maxLength('tipo_unidade', 1)
            ->allowEmptyString('tipo_unidade');

        $validator
            ->integer('codigo_fornecedor_fiscal')
            ->allowEmptyString('codigo_fornecedor_fiscal');

        $validator
            ->scalar('codigo_documento_real')
            ->maxLength('codigo_documento_real', 14)
            ->allowEmptyString('codigo_documento_real');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->scalar('cnes')
            ->maxLength('cnes', 10)
            ->allowEmptyString('cnes');

        $validator
            ->integer('codigo_fornecedor_recebedor')
            ->allowEmptyString('codigo_fornecedor_recebedor');

        return $validator;
    }

    /**
     * @param array $params
     * @return Query
     */
    public function obterEstabelecimentoAutoComplete(array $params)
    {

        $descricao = null;
        $where = [];

        if (isset($params['descricao'])) {
            $descricao = $params['descricao'];
            $where = ["nome LIKE" => "%{$descricao}%"];
        }

        return $this->find()
            ->select(['codigo', 'nome'])
            ->where($where);
    }

    /**
     * @param array $params
     * @return array
     */
    public function obterEstabelecimentoEndereco(array $params)
    {
        $descricao = null;
        $where = '';

        $fields = array(
            'Fornecedores.codigo',
            'Fornecedores.nome',
            'FornecedoresEndereco.logradouro',
            'FornecedoresEndereco.numero',
            'FornecedoresEndereco.complemento',
        );

        $joins  = array(
            array(
                'table' => 'fornecedores_endereco',
                'alias' => 'FornecedoresEndereco',
                'type' => 'INNER',
                'conditions' => 'FornecedoresEndereco.codigo_fornecedor = Fornecedores.codigo',
            ),
        );

        if (isset($params['descricao'])) {
            $descricao = $params['descricao'];
            $where = "nome LIKE '%{$descricao}%' OR FornecedoresEndereco.logradouro LIKE '%{$descricao}%'";
        }

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($where)
            ->hydrate(false)
            ->toArray();

        return $dados;
    }


    /**
     * @param int $codigo_fornecedor
     * @return array
     * @author Rodrigo Franca
     */
    public function getDadosdaEmpresa($codigo_fornecedor)
    {

        $query = "SELECT
                f.codigo codigo
                , f.razao_social
                , f.nome
                , f.tipo_unidade
                , f.codigo_documento
                , f.codigo_documento_real
                , f.ativo ativo
                , fe.codigo codigo_fornecedor_endereco
                , fe.estado_abreviacao
                , fe.estado_descricao
                , fe.cidade
                , fe.bairro
            FROM
                fornecedores f
                INNER JOIN fornecedores_endereco fe ON f.codigo = fe.codigo_fornecedor
            WHERE
                f.codigo = {$codigo_fornecedor};";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;



        $enderecos = TableRegistry::getTableLocator()->get('FornecedoresEndereco');

        $endereco_comercial = $enderecos
            ->find()
            ->where(['codigo_fornecedor' => $codigo_fornecedor])
            ->first()
            ->toArray();
        $endereco_comercial['codigo_fornecedor_endereco'] = $endereco_comercial['codigo'];
        unset($endereco_comercial['codigo']);
        $dados = array_merge($dados, $endereco_comercial);
        return $dados;
    }

    /**
     * @param int $codigo_fornecedor
     * @return array
     */
    public function getDadosGerais(int $codigo_fornecedor)
    {
        $query = "SELECT
                f.acesso_portal
                , f.prestador_qualificado
                , f.data_contratacao
            FROM
                dbo.fornecedores f
            WHERE
                f.codigo = {$codigo_fornecedor};";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }

    /**
     * @param int $codigo_fornecedor
     * @return array
     */
    public function getResponsavelAdministrativo(int $codigo_fornecedor)
    {
        $query = "SELECT
                        f.responsavel_administrativo
                        , f.cnes
                        , f.interno
                    FROM
                        dbo.fornecedores f
                    WHERE
                        f.codigo = {$codigo_fornecedor};";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }


    /**
     * @param int $codigo_fornecedor
     * @return array
     */
    public function getUnidades(int $codigo_fornecedor)
    {

        //monta a query
        $query = "SELECT f.codigo AS codigo,
                    RHHealth.dbo.ufn_decode_utf8_string(CONCAT(f.nome,' / ',f.razao_social)) as descricao
                FROM fornecedores f
                    LEFT JOIN fornecedores_unidades fu ON fu.codigo_fornecedor_matriz = f.codigo or fu.codigo_fornecedor_unidade = f.codigo
                WHERE f.codigo = {$codigo_fornecedor} OR (fu.codigo_fornecedor_matriz = {$codigo_fornecedor} or fu.codigo_fornecedor_unidade = {$codigo_fornecedor})
                GROUP BY f.codigo,f.nome,f.razao_social;";

        // print $query; exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        // debug($dados);exit;

        return $dados;
    }

    public function queryFakeCnpj($like)
    {

        $fields = array(
            'codigo_documento' => 'MAX(SUBSTRING(Fornecedores.codigo_documento, 9, 4))'
        );

        $conditions = "Fornecedores.codigo_documento like '" . $like . "%' ";

        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->first();

        return $dados;
    }
}
