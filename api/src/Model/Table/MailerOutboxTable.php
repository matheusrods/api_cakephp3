<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;


/**
 * MailerOutbox Model
 *
 * @method \App\Model\Entity\MailerOutbox get($primaryKey, $options = [])
 * @method \App\Model\Entity\MailerOutbox newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MailerOutbox[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MailerOutbox|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MailerOutbox saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MailerOutbox patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MailerOutbox[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MailerOutbox findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class MailerOutboxTable extends AppTable
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

        $this->setTable('mailer_outbox');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');      

        $this->addBehavior('Timestamp');
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');
            // ->requirePresence('id', 'create')
            // ->notEmptyString('id');

        $validator
            ->scalar('to')
            ->requirePresence('to', 'create')
            ->notEmptyString('to');

        $validator
            ->scalar('subject')
            ->maxLength('subject', 150)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        // $validator
        //     ->dateTime('sent')
        //     ->allowEmptyDateTime('sent');

        $validator
            ->dateTime('liberar_envio_em')
            ->allowEmptyDateTime('liberar_envio_em');

        $validator
            ->scalar('from')
            ->maxLength('from', 320)
            ->requirePresence('from', 'create')
            ->notEmptyString('from');

        $validator
            ->scalar('cc')
            ->maxLength('cc', 320)
            ->allowEmptyString('cc');

        $validator
            ->scalar('model')
            ->maxLength('model', 255)
            ->allowEmptyString('model');

        $validator
            ->integer('foreign_key')
            ->allowEmptyString('foreign_key');

        $validator
            ->scalar('attachments')
            ->allowEmptyString('attachments');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
