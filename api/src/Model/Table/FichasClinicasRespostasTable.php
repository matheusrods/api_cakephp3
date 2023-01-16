<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * FichasClinicasRespostas Model
 *
 * @method \App\Model\Entity\FichasClinicasResposta get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasResposta findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasClinicasRespostasTable extends AppTable
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

        $this->setTable('fichas_clinicas_respostas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        // $this->belongsTo('FichasClinicas')
        //     ->setForeignKey('codigo_ficha_clinica');
            
        // $this->belongsTo('FichasClinicasQuestoes')
        //     ->setForeignKey('codigo_ficha_clinica_questao');
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
            ->integer('codigo_ficha_clinica_questao')
            ->requirePresence('codigo_ficha_clinica_questao', 'create')
            ->notEmptyString('codigo_ficha_clinica_questao');

        $validator
            ->scalar('resposta')
            ->maxLength('resposta', 5000)
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
            ->integer('codigo_ficha_clinica')
            ->requirePresence('codigo_ficha_clinica', 'create')
            ->notEmptyString('codigo_ficha_clinica');

        $validator
            ->scalar('parentesco')
            ->maxLength('parentesco', 50)
            ->allowEmptyString('parentesco');

        return $validator;
    }
}
