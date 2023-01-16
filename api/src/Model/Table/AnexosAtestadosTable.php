<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
//use Cake\ORM\Table;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * AnexosAtestados Model
 *
 * @method \App\Model\Entity\AnexosAtestado get($primaryKey, $options = [])
 * @method \App\Model\Entity\AnexosAtestado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AnexosAtestado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AnexosAtestado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnexosAtestado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AnexosAtestado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AnexosAtestado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AnexosAtestado findOrCreate($search, callable $callback = null, $options = [])
 */
class AnexosAtestadosTable extends AppTable
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

        $this->setTable('anexos_atestados');
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
            ->integer('codigo_atestado')
            ->requirePresence('codigo_atestado', 'create')
            ->notEmptyString('codigo_atestado');

        $validator
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 255)
            ->requirePresence('caminho_arquivo', 'create')
            ->notEmptyString('caminho_arquivo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        // $validator
        //     ->dateTime('data_inclusao')
        //     ->requirePresence('data_inclusao', 'create')
        //     ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }


}
