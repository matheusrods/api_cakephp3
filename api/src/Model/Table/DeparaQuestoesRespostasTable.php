<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DeparaQuestoesRespostas Model
 *
 * @method \App\Model\Entity\DeparaQuestoesResposta get($primaryKey, $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DeparaQuestoesResposta findOrCreate($search, callable $callback = null, $options = [])
 */
class DeparaQuestoesRespostasTable extends Table
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

        $this->setTable('depara_questoes_respostas');
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
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->integer('codigo_questao_questionario')
            ->allowEmptyString('codigo_questao_questionario');

        $validator
            ->integer('codigo_resposta_questionario')
            ->allowEmptyString('codigo_resposta_questionario');

        $validator
            ->scalar('resposta_ficha_clinica')
            ->maxLength('resposta_ficha_clinica', 100)
            ->allowEmptyString('resposta_ficha_clinica');

        return $validator;
    }
}
