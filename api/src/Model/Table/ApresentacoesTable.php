<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Apresentacoes Model
 *
 * @method \App\Model\Entity\Apresentaco get($primaryKey, $options = [])
 * @method \App\Model\Entity\Apresentaco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Apresentaco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Apresentaco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Apresentaco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Apresentaco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Apresentaco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Apresentaco findOrCreate($search, callable $callback = null, $options = [])
 */
class ApresentacoesTable extends AppTable
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

        $this->setTable('apresentacoes');
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
            ->scalar('descricao')
            ->maxLength('descricao', 500)
            ->allowEmptyString('descricao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        return $validator;
    }

    /***
     * Busca os códigos de apresentação para os medicamentos
     * @param $apresentacoes
     * @return Query
     */

    public function getApresentacao($apresentacoes){

        $select = ['codigo', 'descricao'];
        $group = ['codigo', 'descricao'];
        // $where = [];

        foreach($apresentacoes as $apresentacao) {
            $codigos[] = $apresentacao->codigo_apresentacao;
        //     array_push($where, ['codigo' => $apresentacao->codigo_apresentacao]);
        }

        $where = ['codigo IN ('.implode(',',$codigos).')'];

        // debug($where);

        // $where = ['codigo IN ('..')'];

        return $this->find()
            ->select($select)
            ->where($where)
            ->group($group);
    }
}
