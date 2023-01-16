<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FichasAssistenciaisRespostas Model
 *
 * @method \App\Model\Entity\FichasAssistenciaisResposta get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasAssistenciaisResposta findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasAssistenciaisRespostasTable extends Table
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

        $this->setTable('fichas_assistenciais_respostas');
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
            ->integer('codigo_ficha_assistencial_questao')
            ->requirePresence('codigo_ficha_assistencial_questao', 'create')
            ->notEmptyString('codigo_ficha_assistencial_questao');

        $validator
            ->scalar('resposta')
            ->allowEmptyString('resposta');

        $validator
            ->scalar('campo_livre')
            ->maxLength('campo_livre', 5000)
            ->allowEmptyString('campo_livre');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_ficha_assistencial')
            ->requirePresence('codigo_ficha_assistencial', 'create')
            ->notEmptyString('codigo_ficha_assistencial');

        $validator
            ->scalar('parentesco')
            ->maxLength('parentesco', 50)
            ->allowEmptyString('parentesco');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        return $validator;
    }
}
