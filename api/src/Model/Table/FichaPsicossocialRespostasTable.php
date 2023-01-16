<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FichaPsicossocialRespostas Model
 *
 * @method \App\Model\Entity\FichaPsicossocialResposta get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialResposta findOrCreate($search, callable $callback = null, $options = [])
 */
class FichaPsicossocialRespostasTable extends Table
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

        $this->setTable('ficha_psicossocial_respostas');
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
            ->scalar('resposta')
            ->maxLength('resposta', 500)
            ->allowEmptyString('resposta');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('ordem')
            ->allowEmptyString('ordem');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_ficha_psicossocial')
            ->requirePresence('codigo_ficha_psicossocial', 'create')
            ->notEmptyString('codigo_ficha_psicossocial');

        $validator
            ->integer('codigo_ficha_psicossocial_perguntas')
            ->requirePresence('codigo_ficha_psicossocial_perguntas', 'create')
            ->notEmptyString('codigo_ficha_psicossocial_perguntas');

        return $validator;
    }
}
