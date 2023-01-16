<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FichaPsicossocialPerguntas Model
 *
 * @method \App\Model\Entity\FichaPsicossocialPergunta get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichaPsicossocialPergunta findOrCreate($search, callable $callback = null, $options = [])
 */
class FichaPsicossocialPerguntasTable extends Table
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

        $this->setTable('ficha_psicossocial_perguntas');
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
            ->scalar('pergunta')
            ->maxLength('pergunta', 500)
            ->allowEmptyString('pergunta');

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

        return $validator;
    }

    public function listarPerguntasRespostasFormatadas(){
        $fields = array(
            'codigo_pergunta' => 'FichaPsicossocialPerguntas.codigo',
            'ordem' => 'FichaPsicossocialPerguntas.ordem',
            'label_pergunta' => 'FichaPsicossocialPerguntas.pergunta',
        );

        $perguntas = $this->find()
            ->select($fields)
            ->where(array('FichaPsicossocialPerguntas.ativo' => 1))
            ->order(array('FichaPsicossocialPerguntas.ordem ASC'))
            ->toArray();

        $data = array();
        if(!empty($perguntas)) {
            foreach ($perguntas as $key => $pergunta) {
                $ultima = end($perguntas);

                if($pergunta['codigo_pergunta'] == $ultima['codigo_pergunta']){
                    $perguntas[$key]['respostas'] = array(
                        array('codigo' => '0', 'label' => 'Não', 'codigo_proxima_pergunta' => null),
                        array('codigo' => '1', 'label' => 'Sim', 'codigo_proxima_pergunta' => null)
                    );
                } else {
                    $perguntas[$key]['respostas'] = array(
                        array('codigo' => '0', 'label' => 'Não', 'codigo_proxima_pergunta' => $perguntas[$key + 1]['codigo_pergunta']),
                        array('codigo' => '1', 'label' => 'Sim', 'codigo_proxima_pergunta' => $perguntas[$key + 1]['codigo_pergunta'])
                    );
                }
            }
            
            $data['perguntas'] = $perguntas;
        }

        return $data;
    }
}
