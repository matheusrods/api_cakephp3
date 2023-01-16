<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcoesMelhoriasAnexo Model
 *
 * @method \App\Model\Entity\AcoesMelhoriasAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcoesMelhoriasAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class AcoesMelhoriasAnexosTable extends Table
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

        $this->setTable('acoes_melhorias_anexos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_acao_melhoria_anexo');
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
            ->integer('codigo_acao_melhoria')
            ->requirePresence('codigo_acao_melhoria', 'create')
            ->notEmptyString('codigo_acao_melhoria');

        $validator
            ->scalar('arquivo')
            ->allowEmptyString('arquivo');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->requirePresence('arquivo_url', 'create')
            ->notEmptyString('arquivo_url');

        $validator
            ->scalar('arquivo_nome')
            ->maxLength('arquivo_nome', 255)
            ->requirePresence('arquivo_nome', 'create')
            ->notEmptyString('arquivo_nome');

        $validator
            ->scalar('arquivo_tamanho')
            ->maxLength('arquivo_tamanho', 10)
            ->requirePresence('arquivo_tamanho', 'create')
            ->notEmptyString('arquivo_tamanho');

        $validator
            ->integer('arquivo_tipo')
            ->requirePresence('arquivo_tipo', 'create')
            ->notEmptyString('arquivo_tipo');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

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
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }

    public function getByActionId($improvementActionCode)
    {
        $data = [
            'error' => null,
            'registry' => null,
        ];

        $fields = [
            'AcoesMelhoriasAnexos.codigo',
            'AcoesMelhoriasAnexos.codigo_acao_melhoria',
            'AcoesMelhoriasAnexos.arquivo',
            'AcoesMelhoriasAnexos.arquivo_nome',
            'AcoesMelhoriasAnexos.arquivo_tamanho',
            'AcoesMelhoriasAnexos.arquivo_url',
            'AcoesMelhoriasAnexos.arquivo_tipo',
            'AcoesMelhoriasAnexos.codigo_usuario_inclusao',
            'AcoesMelhoriasAnexos.data_inclusao',
        ];

        $conditions = [
            'AcoesMelhoriasAnexos.ativo' => 1,
            'AcoesMelhoriasAnexos.codigo_acao_melhoria' => $improvementActionCode,
        ];

        try {
            $files = $this->find()
                ->select($fields)
                ->where($conditions)
                ->all()
                ->toArray();

            $data['registry'] = $files;

            return $data;
        } catch (\Exception $exception) {
            $data = [
                'error' => [
                    'message' => $exception->getMessage(),
                ],
                'registry' => null,
            ];

            return $data;
        }
    }
}
