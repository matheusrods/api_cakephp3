<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasSolicitacao Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasSolicitacao findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasSolicitacoesTable extends Table
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

        $this->setTable('acoes_melhorias_solicitacoes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasOne('AcoesMelhoriasSolicitacoesTipo', [
            'bindingKey' => 'codigo_acao_melhoria_solicitacao_tipo',
            'foreignKey' => 'codigo',
            'joinTable' => 'acoes_melhorias_solicitacoes_tipo',
            'propertyName' => 'tipo',
        ]);

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria_solicitacao');
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
            ->integer('codigo_acao_melhoria_solicitacao_antecedente')
            ->allowEmptyString('codigo_acao_melhoria_solicitacao_antecedente');

        $validator
            ->integer('codigo_acao_melhoria', 'O campo de ação de melhoria precisa ser um número inteiro.')
            ->requirePresence('codigo_acao_melhoria', 'create', 'O campo de ação de melhoria é obrigatório.')
            ->notEmptyString('codigo_acao_melhoria', 'O campo de ação de melhoria não pode ser deixado em branco.');

        $validator
            ->integer('codigo_acao_melhoria_solicitacao_tipo', 'O campo de tipo precisa ser um número inteiro.')
            ->requirePresence('codigo_acao_melhoria_solicitacao_tipo', 'create', 'O campo de tipo é obrigatório.')
            ->notEmptyString('codigo_acao_melhoria_solicitacao_tipo', 'O campo de tipo não pode ser deixado em branco.');

        $validator
            ->integer('codigo_usuario_solicitado', 'O campo de usuário solicitado precisa ser um número inteiro.')
            ->allowEmptyString('codigo_usuario_solicitado');

        $validator
            ->integer('codigo_novo_usuario_responsavel', 'O campo de novo usuário responsável precisa ser um número inteiro.')
            ->allowEmptyString('codigo_novo_usuario_responsavel');

        $validator
            ->integer('usuario_solicitado_tipo', 'O campo de usuário solicitado tipo precisa ser um número inteiro.')
            ->allowEmptyString('usuario_solicitado_tipo');

        $validator
            ->integer('status', 'O campo de status precisa ser um número inteiro.')
            ->requirePresence('status', 'create', 'O campo de status é obrigatório.')
            ->notEmptyString('status', 'O campo de status não pode ser deixado em branco.');

        $validator
            ->date('novo_prazo', ['ymd'], 'O campo de prazo precisa ser uma data válida.')
            ->allowEmptyDateTime('novo_prazo');

        $validator
            ->scalar('justificativa_solicitacao')
            ->allowEmptyString('justificativa_solicitacao');

        $validator
            ->scalar('justificativa_recusa')
            ->allowEmptyString('justificativa_recusa');

        $validator
            ->boolean('alteracao_sistema')
            ->allowEmptyString('alteracao_sistema');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }

    public function getByActionId($actionCode)
    {
        $conditions = [
            "AcoesMelhoriasSolicitacoes.codigo_acao_melhoria = $actionCode",
            'AcoesMelhoriasSolicitacoes.data_remocao IS NULL',
        ];

        try {
            $requests = $this->find()
                ->where($conditions)
                ->contain(['AcoesMelhoriasSolicitacoesTipo'])
                ->all()
                ->toArray();

            return $requests;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function pendencies(int $userCode)
    {
        $fields = [
            'codigo_acao_melhoria_solicitacao_tipo' => 'AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo',
            'quantidade' => 'COUNT(AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo)',
        ];

        $conditions = [
            "(AcoesMelhoriasSolicitacoes.usuario_solicitado_tipo = 2 AND $userCode IN (
                SELECT UsuariosResponsaveis.codigo_usuario FROM usuarios_responsaveis UsuariosResponsaveis
                WHERE UsuariosResponsaveis.codigo_cliente = AcoesMelhorias.codigo_cliente_observacao AND UsuariosResponsaveis.data_remocao IS NULL
            ) OR AcoesMelhoriasSolicitacoes.codigo_usuario_solicitado = $userCode)",
            'AcoesMelhoriasSolicitacoes.status' => 1,
            'AcoesMelhoriasSolicitacoes.data_remocao IS NULL',
            'AcoesMelhorias.data_remocao IS NULL'
        ];

        $joins = [
            [
                'table' => 'acoes_melhorias',
                'alias' => 'AcoesMelhorias',
                'type' => 'INNER',
                'conditions' => 'AcoesMelhorias.codigo = AcoesMelhoriasSolicitacoes.codigo_acao_melhoria',
            ]
        ];

        try {
            $requests = $this->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->group(['AcoesMelhoriasSolicitacoes.codigo_acao_melhoria_solicitacao_tipo'])
                ->all()
                ->toArray();

            return $requests;
        } catch (\Exception $exception) {
            return [];
        }
    }
}
