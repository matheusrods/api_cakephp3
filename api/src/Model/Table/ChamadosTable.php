<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Chamados Model
 *
 * @method \App\Model\Entity\Chamado get($primaryKey, $options = [])
 * @method \App\Model\Entity\Chamado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Chamado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Chamado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Chamado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Chamado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Chamado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Chamado findOrCreate($search, callable $callback = null, $options = [])
 */
class ChamadosTable extends Table
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

        $this->setTable('chamados');
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_chamado_tipo')
            ->requirePresence('codigo_chamado_tipo', 'create')
            ->notEmptyString('codigo_chamado_tipo');

        $validator
            ->integer('codigo_chamado_status')
            ->requirePresence('codigo_chamado_status', 'create')
            ->notEmptyString('codigo_chamado_status');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_original')
            ->requirePresence('data_original', 'create')
            ->notEmptyDateTime('data_original');

        $validator
            ->dateTime('data_adiar_de')
            ->allowEmptyDateTime('data_adiar_de');

        $validator
            ->dateTime('data_adiar_para')
            ->allowEmptyDateTime('data_adiar_para');

        $validator
            ->scalar('razao_adiar')
            ->maxLength('razao_adiar', 255)
            ->allowEmptyString('razao_adiar');

        $validator
            ->dateTime('data_cancelamento')
            ->allowEmptyDateTime('data_cancelamento');

        $validator
            ->scalar('razao_cancelamento')
            ->maxLength('razao_cancelamento', 255)
            ->allowEmptyString('razao_cancelamento');

        $validator
            ->integer('responsavel')
            ->requirePresence('responsavel', 'create')
            ->notEmptyString('responsavel');

        $validator
            ->scalar('descricao_levantamento')
            ->allowEmptyString('descricao_levantamento');

        return $validator;
    }
}
