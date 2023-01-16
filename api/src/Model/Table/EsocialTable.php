<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Esocial Model
 *
 * @method \App\Model\Entity\Esocial get($primaryKey, $options = [])
 * @method \App\Model\Entity\Esocial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Esocial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Esocial|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Esocial saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Esocial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Esocial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Esocial findOrCreate($search, callable $callback = null, $options = [])
 */
class EsocialTable extends AppTable
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

        $this->setTable('esocial');
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
            ->integer('tabela')
            ->requirePresence('tabela', 'create')
            ->notEmptyString('tabela');

        $validator
            ->integer('codigo_pai')
            ->allowEmptyString('codigo_pai');

        $validator
            ->scalar('codigo_descricao')
            ->maxLength('codigo_descricao', 255)
            ->allowEmptyString('codigo_descricao');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->scalar('coluna_adicional')
            ->maxLength('coluna_adicional', 255)
            ->allowEmptyString('coluna_adicional');

        $validator
            ->scalar('coluna_adicional2')
            ->maxLength('coluna_adicional2', 255)
            ->allowEmptyString('coluna_adicional2');

        $validator
            ->integer('nivel')
            ->requirePresence('nivel', 'create')
            ->notEmptyString('nivel');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }

    //Carrega informaçoes da TABELA 18 ESOCIAL.
	public function obterLista( $params )
	{
        
        if(isset($params['descricao'])){
            $descricao = $params['descricao'];
            $conditions = ['tabela' => 18, 'ativo' => 1, "descricao LIKE"=> "%{$descricao}%"];
        } else {
            $conditions = ['tabela' => 18, 'ativo' => 1];
        }

        $fields = ['codigo','descricao' => "CONCAT(codigo_descricao,' - ', descricao)"];

        return $this->find()
            ->select($fields)
            ->where($conditions)
            ->order(['descricao ASC']);;
	}    

}
