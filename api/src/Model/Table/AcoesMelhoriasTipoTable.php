<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasTipo Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasTipoTable extends Table
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

        $this->setTable('acoes_melhorias_tipo');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria_tipo');
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
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

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

        return $validator;
    }

    public function getAll($clientCode)
    {
        $conditions = [];

        if ($clientCode) {
            $this->Usuario = TableRegistry::get('Usuario');

            $economicGroup = $this->Usuario->getClienteGrupoEconomico((int) $clientCode);

            if (!empty($economicGroup)) {
                $conditions['codigo_cliente'] = (int) $economicGroup['codigo_cliente'];
            } else {
                return [];
            }
        }

        try {

            $fields = [
                
                "codigo",
                "descricao" => "RHHealth.dbo.ufn_decode_utf8_string(descricao)",
                "ativo",
                "codigo_usuario_inclusao",
                "codigo_usuario_alteracao",
                "data_inclusao",
                "data_alteracao",
                "codigo_cliente",
            ];

            $conditions['ativo'] = 1;
            $data = $this->find()
                ->select($fields)
                ->where($conditions)
                ->all()
                ->toArray();

            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }
}
