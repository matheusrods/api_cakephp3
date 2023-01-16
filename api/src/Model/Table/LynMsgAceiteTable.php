<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * LynMsgAceite Model
 *
 * @method \App\Model\Entity\LynMsgAceite get($primaryKey, $options = [])
 * @method \App\Model\Entity\LynMsgAceite newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LynMsgAceite[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LynMsgAceite|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMsgAceite saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMsgAceite patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LynMsgAceite[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LynMsgAceite findOrCreate($search, callable $callback = null, $options = [])
 */
class LynMsgAceiteTable extends AppTable
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

        $this->setTable('lyn_msg_aceite');
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
            ->integer('codigo_lyn_msg')
            ->allowEmptyString('codigo_lyn_msg');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }
}
