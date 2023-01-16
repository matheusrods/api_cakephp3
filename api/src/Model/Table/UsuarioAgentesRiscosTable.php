<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuarioAgentesRiscos Model
 *
 * @method \App\Model\Entity\UsuarioAgentesRisco get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioAgentesRisco findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioAgentesRiscosTable extends Table
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

        $this->setTable('usuario_agentes_riscos');
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
            ->integer('codigo_arrtpa_ri')
            ->requirePresence('codigo_arrtpa_ri', 'create')
            ->notEmptyString('codigo_arrtpa_ri');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_assinatura')
            ->notEmptyDateTime('data_assinatura');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->requirePresence('arquivo_url', 'create')
            ->notEmptyString('arquivo_url');

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
}
