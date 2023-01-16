<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LynMenu Model
 *
 * @property \App\Model\Table\ClienteTable&\Cake\ORM\Association\BelongsToMany $Cliente
 * @property \App\Model\Table\ClienteLogTable&\Cake\ORM\Association\BelongsToMany $ClienteLog
 *
 * @method \App\Model\Entity\LynMenu get($primaryKey, $options = [])
 * @method \App\Model\Entity\LynMenu newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LynMenu[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LynMenu|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMenu saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LynMenu patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LynMenu[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LynMenu findOrCreate($search, callable $callback = null, $options = [])
 */
class LynMenuTable extends Table
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

        $this->setTable('lyn_menu');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Cliente', [
            'foreignKey' => 'lyn_menu_id',
            'targetForeignKey' => 'cliente_id',
            'joinTable' => 'lyn_menu_cliente',
        ]);
        $this->belongsToMany('ClienteLog', [
            'foreignKey' => 'lyn_menu_id',
            'targetForeignKey' => 'cliente_log_id',
            'joinTable' => 'lyn_menu_cliente_log',
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
            ->allowEmptyString('descricao');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
