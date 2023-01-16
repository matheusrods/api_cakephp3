<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PerigosAspectos Model
 *
 * @method \App\Model\Entity\PerigosAspecto get($primaryKey, $options = [])
 * @method \App\Model\Entity\PerigosAspecto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PerigosAspecto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PerigosAspecto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PerigosAspecto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PerigosAspecto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PerigosAspecto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PerigosAspecto findOrCreate($search, callable $callback = null, $options = [])
 */
class PerigosAspectosTable extends Table
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

        $this->setTable('perigos_aspectos');
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
            ->integer('codigo_risco_tipo')
            ->requirePresence('codigo_risco_tipo', 'create')
            ->notEmptyString('codigo_risco_tipo');

        $validator
            ->integer('codigo_perigo_aspecto_tipo')
            ->requirePresence('codigo_perigo_aspecto_tipo', 'create')
            ->notEmptyString('codigo_perigo_aspecto_tipo');

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

    public function getPerigosAspectos($codigo_perigo_aspecto=null)
    {

        if(is_null($codigo_perigo_aspecto)) {
            return null;
        }
        
        //campos do select
        $fields = [
            'codigo',
            'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)',
        ];

        $conditions = "PerigosAspectos.codigo = {$codigo_perigo_aspecto}";
        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->where($conditions)
            ->hydrate(false)
            ->first();

        return $dados;
    }
}
