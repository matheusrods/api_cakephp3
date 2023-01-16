<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

use App\Utils\EncodingUtil;
use Cake\Log\Log;

/**
 * TipoNotificacao Model
 *
 * @method \App\Model\Entity\TipoNotificacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\TipoNotificacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TipoNotificacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoNotificacao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoNotificacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TipoNotificacao findOrCreate($search, callable $callback = null, $options = [])
 */

class TipoNotificacaoTable extends AppTable

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

        $this->setTable('tipo_notificacao');
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
            ->scalar('tipo')
            ->maxLength('tipo', 50)
            ->allowEmptyString('tipo');

        $validator
            ->boolean('notificacao_especifica')
            ->allowEmptyString('notificacao_especifica');

        return $validator;
    }
}
