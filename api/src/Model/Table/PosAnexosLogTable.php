<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable as Table;
use Cake\Validation\Validator;

/**
 * PosAnexosLog Model
 *
 * @method \App\Model\Entity\PosAnexosLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosAnexosLog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosAnexosLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexosLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosAnexosLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosAnexosLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexosLog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexosLog findOrCreate($search, callable $callback = null, $options = [])
 */
class PosAnexosLogTable extends Table
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

        $this->setTable('pos_anexos_log');
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
            ->integer('codigo_pos_anexo')
            ->requirePresence('codigo_pos_anexo', 'create')
            ->notEmptyString('codigo_pos_anexo');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->requirePresence('codigo_pos_ferramenta', 'create')
            ->notEmptyString('codigo_pos_ferramenta');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->requirePresence('arquivo_url', 'create')
            ->notEmptyString('arquivo_url');

        $validator
            ->scalar('arquivo_url_curta')
            ->maxLength('arquivo_url_curta', 255)
            ->allowEmptyString('arquivo_url_curta');

        $validator
            ->integer('arquivo_tipo')
            ->allowEmptyString('arquivo_tipo');

        $validator
            ->scalar('arquivo_extensao')
            ->maxLength('arquivo_extensao', 10)
            ->allowEmptyString('arquivo_extensao');

        $validator
            ->scalar('arquivo_tamanho_bytes')
            ->maxLength('arquivo_tamanho_bytes', 100)
            ->allowEmptyString('arquivo_tamanho_bytes');

        $validator
            ->scalar('arquivo_hash')
            ->maxLength('arquivo_hash', 100)
            ->allowEmptyString('arquivo_hash');            

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->allowEmptyString('acao_sistema');

        return $validator;
    }
}
