<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;



/**
 * PosSwtFormQuestao Model
 *
 * @method \App\Model\Entity\PosSwtFormQuestao get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtFormQuestao findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormQuestaoTable extends AppTable
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

        $this->setTable('pos_swt_form_questao');
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
            ->integer('codigo_form')
            ->allowEmptyString('codigo_form');

        $validator
            ->integer('codigo_form_titulo')
            ->allowEmptyString('codigo_form_titulo');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('questao')
            ->maxLength('questao', 255)
            ->allowEmptyString('questao');

        $validator
            ->integer('ordem')
            ->allowEmptyString('ordem');

        $validator
            ->scalar('informacao')
            ->allowEmptyString('informacao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }


}
