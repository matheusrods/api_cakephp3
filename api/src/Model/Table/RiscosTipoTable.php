<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RiscosTipo Model
 *
 * @method \App\Model\Entity\RiscosTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\RiscosTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RiscosTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RiscosTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RiscosTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RiscosTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class RiscosTipoTable extends Table
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

        $this->setTable('riscos_tipo');
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
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('cor')
            ->maxLength('cor', 255)
            ->allowEmptyString('cor');

        $validator
            ->scalar('icone')
            ->maxLength('icone', 255)
            ->allowEmptyString('icone');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        return $validator;
    }

    public function getRiscosTipos($codigo_risco_tipo=null)
    {

        if(is_null($codigo_risco_tipo)) {
            return null;
        }

        //campos do select
        $fields = [
            'codigo',
            'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
        ];

        $conditions = "RiscosTipo.codigo = {$codigo_risco_tipo}";
            //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->hydrate(false)
            ->first();

        return $dados;
    }
}
