<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * PosCriticidade Model
 *
 * @method \App\Model\Entity\PosCriticidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosCriticidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosCriticidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosCriticidade|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosCriticidade saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosCriticidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosCriticidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosCriticidade findOrCreate($search, callable $callback = null, $options = [])
 */
class PosCriticidadeTable extends Table
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

        $this->setTable('pos_criticidade');
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
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('cor')
            ->maxLength('cor', 255)
            ->requirePresence('cor', 'create')
            ->notEmptyString('cor');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->requirePresence('codigo_pos_ferramenta', 'create')
            ->notEmptyString('codigo_pos_ferramenta');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

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
            ->integer('valor_inicio')
            ->allowEmptyString('valor_inicio');

        $validator
            ->integer('valor_fim')
            ->allowEmptyString('valor_fim');

        return $validator;
    }

    public function getAll($codigo_cliente, $codigo_pos_ferramenta)
    {
        try {
            $data = $this->find()
                ->where(['codigo_cliente' => $codigo_cliente, 'codigo_pos_ferramenta' => $codigo_pos_ferramenta])
                ->toArray();

            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }
}
