<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CamposIdiomasAso Model
 *
 * @method \App\Model\Entity\CamposIdiomasAso get($primaryKey, $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CamposIdiomasAso findOrCreate($search, callable $callback = null, $options = [])
 */
class CamposIdiomasAsoTable extends Table
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

        $this->setTable('campos_idiomas_aso');
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
            ->scalar('campo')
            ->maxLength('campo', 100)
            ->requirePresence('campo', 'create')
            ->notEmptyString('campo');

        $validator
            ->scalar('titulo')
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->integer('idioma')
            ->requirePresence('idioma', 'create')
            ->notEmptyString('idioma');

        return $validator;
    }

    public function listar($codigo_idioma){
        
        //var_dump($codigo_idioma);

        $aux = array();
        $retorno = array();

        $conditions = array("idioma IN ($codigo_idioma)");
        $return = $this->find()->select(['campo','titulo'])->where($conditions)->hydrate(false)->all()->toArray();

        // debug($return);exit;//completo

        foreach($return as $keyr => $v){
            // $v = $v['CamposIdiomasAso'];
        
            //debug($v);

            $campo = $v['campo'];
            $titulo = "";

            foreach($return as $c){
                // $c = $c['CamposIdiomasAso'];

                if($c['campo'] == $campo){
                    //foi pedido que colocasse o espaco para que o titulo traduzido foi bem compreendido no relatorio
                    $titulo .= "/".$c['titulo'];                           
                }
            }
            $v['titulo'] = substr($titulo,1);
            
            $aux[] = $v;
        } 
        
        $aux = array_map("unserialize", array_unique(array_map("serialize", $aux)));
        
        //debug($aux);//exit;
        return ($aux);
        
    }
}
