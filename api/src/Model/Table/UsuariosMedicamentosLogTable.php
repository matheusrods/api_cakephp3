<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuariosMedicamentosLog Model
 *
 * @method \App\Model\Entity\UsuariosMedicamentosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamentosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosMedicamentosLogTable extends Table
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

        $this->setTable('usuarios_medicamentos_log');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

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
            ->integer('codigo_usuarios_medicamentos')
            ->requirePresence('codigo_usuarios_medicamentos', 'create')
            ->notEmptyString('codigo_usuarios_medicamentos');

        $validator
            ->integer('codigo_medicamentos')
            ->allowEmptyString('codigo_medicamentos');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->allowEmptyString('frequencia_dias');

        $validator
            ->allowEmptyString('frequencia_horarios');

        $validator
            ->allowEmptyString('uso_continuo');

        $validator
            ->scalar('dias_da_semana')
            ->maxLength('dias_da_semana', 50)
            ->allowEmptyString('dias_da_semana');

        $validator
            ->allowEmptyString('frequencia_uso');

        $validator
            ->scalar('horario_inicio_uso')
            ->maxLength('horario_inicio_uso', 5)
            ->allowEmptyString('horario_inicio_uso');

        $validator
            ->integer('quantidade')
            ->allowEmptyString('quantidade');

        $validator
            ->scalar('recomendacao_medica')
            ->allowEmptyString('recomendacao_medica');

        $validator
            ->scalar('foto_receita')
            ->maxLength('foto_receita', 255)
            ->allowEmptyString('foto_receita');

        $validator
            ->allowEmptyString('frequencia_dias_intercalados');

        $validator
            ->scalar('periodo_tratamento_inicio')
            ->allowEmptyString('periodo_tratamento_inicio');

        $validator
            ->scalar('periodo_tratamento_termino')
            ->allowEmptyString('periodo_tratamento_termino');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_apresentacao')
            ->allowEmptyString('codigo_apresentacao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('acao')
            ->allowEmptyString('acao');

        return $validator;
    }
}
