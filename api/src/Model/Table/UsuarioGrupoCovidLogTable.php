<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuarioGrupoCovidLog Model
 *
 * @method \App\Model\Entity\UsuarioGrupoCovidLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioGrupoCovidLog findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioGrupoCovidLogTable extends Table
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

        $this->setTable('usuario_grupo_covid_log');
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
            ->integer('codigo_usuario_grupo_covid')
            ->requirePresence('codigo_usuario_grupo_covid', 'create')
            ->notEmptyString('codigo_usuario_grupo_covid');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_grupo_covid')
            ->requirePresence('codigo_grupo_covid', 'create')
            ->notEmptyString('codigo_grupo_covid');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 20)
            ->requirePresence('cpf', 'create')
            ->notEmptyString('cpf');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyString('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyString('data_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('acao')
            ->allowEmptyString('acao');

        return $validator;
    }
}
