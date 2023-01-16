<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ValidarTokenTipo Model
 *
 * @property \App\Model\Table\SistemaTable&\Cake\ORM\Association\BelongsToMany $Sistema
 *
 * @method \App\Model\Entity\ValidarTokenTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ValidarTokenTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class ValidarTokenTipoTable extends Table
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

        $this->setTable('validar_token_tipo');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Sistema', [
            'foreignKey' => 'validar_token_tipo_id',
            'targetForeignKey' => 'sistema_id',
            'joinTable' => 'sistema_validar_token_tipo',
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
            ->scalar('descricao')
            ->maxLength('descricao', 50)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

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

        return $validator;
    }
}
