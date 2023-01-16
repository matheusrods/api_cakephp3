<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * CaracteristicasQuestionarios Model
 *
 * @method \App\Model\Entity\CaracteristicasQuestionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CaracteristicasQuestionario findOrCreate($search, callable $callback = null, $options = [])
 */
class CaracteristicasQuestionariosTable extends AppTable
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

        $this->setTable('caracteristicas_questionarios');
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
            ->integer('codigo_caracteristica')
            ->requirePresence('codigo_caracteristica', 'create')
            ->notEmptyString('codigo_caracteristica');

        $validator
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        return $validator;
    }

    /**
     * [getTextosQuestionarios description]
     * 
     * metodo para pegar os textos conforme a resposta do questionario
     * 
     * @param  [type] $codigo_questionario [description]
     * @param  [type] $codigos_questoes    [description]
     * @return [type]                      [description]
     */
    public function getTextosQuestionarios($codigo_questionario, $codigos_questoes)
    {

        //campos
        $fields = array(
            'titulo' => 'Caracteristicas.titulo',
            'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(Caracteristicas.descricao)'
        );

        // debug($fields);

        //joins
        $joins = array(
            array(
                'table' => 'caracteristicas',
                'alias' => 'Caracteristicas',
                'type' => 'INNER',
                'conditions' => 'CaracteristicasQuestionarios.codigo_caracteristica = Caracteristicas.codigo'
            ),
            array(
                'table' => 'caracteristicas_questoes',
                'alias' => 'CaracteristicasQuestoes',
                'type' => 'INNER',
                'conditions' => 'CaracteristicasQuestoes.codigo_caracteristica = Caracteristicas.codigo'
            ),
        );

        $codigos_questoes = implode(',',$codigos_questoes);

        //wheres
        $conditions = array(
                        'Caracteristicas.codigo_empresa'=> 1,
                        'Caracteristicas.ativo'=> 1,
                        'CaracteristicasQuestionarios.codigo_questionario'=> $codigo_questionario,
                        'CaracteristicasQuestoes.codigo_questao IN ('.$codigos_questoes.')',
                    );

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)            
            ->hydrate(false)
            ->all()
            ->toArray();

        //print_r($dados->sql());
        //debug($dados);exit;

        return $dados;


    }//fim getTextosQuestionarios

}
