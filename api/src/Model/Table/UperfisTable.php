<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Uperfis Model
 *
 * @method \App\Model\Entity\Uperfi get($primaryKey, $options = [])
 * @method \App\Model\Entity\Uperfi newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Uperfi[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Uperfi|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Uperfi saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Uperfi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Uperfi[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Uperfi findOrCreate($search, callable $callback = null, $options = [])
 */
class UperfisTable extends Table
{
    const MEDICO_CLIENTE = 19;
    const MEDICO_INTERNO = 48;
    const MEDICO_COORDENADOR = 27;
    const ENFERMAGEM_CLIENTE = 20;
    const GERAL = 26;
    const ADMIN = 1;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('uperfis');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
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
            ->scalar('descricao')
            ->maxLength('descricao', 128)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->boolean('perfil_cliente')
            ->allowEmptyString('perfil_cliente');

        $validator
            ->integer('codigo_tipo_perfil')
            ->allowEmptyString('codigo_tipo_perfil');

        $validator
            ->integer('codigo_pai')
            ->allowEmptyString('codigo_pai');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
