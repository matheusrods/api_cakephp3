<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AparelhosAudiometricos Model
 *
 * @property \App\Model\Table\ResultadosTable&\Cake\ORM\Association\BelongsToMany $Resultados
 *
 * @method \App\Model\Entity\AparelhosAudiometrico get($primaryKey, $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AparelhosAudiometrico findOrCreate($search, callable $callback = null, $options = [])
 */
class AparelhosAudiometricosTable extends Table
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

        $this->setTable('aparelhos_audiometricos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Resultados', [
            'foreignKey' => 'aparelhos_audiometrico_id',
            'targetForeignKey' => 'resultado_id',
            'joinTable' => 'aparelhos_audiometricos_resultados'
        ]);
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
            ->scalar('fabricante')
            ->maxLength('fabricante', 255)
            ->allowEmptyString('fabricante');

        $validator
            ->dateTime('data_afericao')
            ->requirePresence('data_afericao', 'create')
            ->notEmptyDateTime('data_afericao');

        $validator
            ->dateTime('data_proxima_afericao')
            ->allowEmptyDateTime('data_proxima_afericao');

        $validator
            ->scalar('empresa_afericao')
            ->maxLength('empresa_afericao', 255)
            ->allowEmptyString('empresa_afericao');

        $validator
            ->integer('disponivel_empresas')
            ->requirePresence('disponivel_empresas', 'create')
            ->notEmptyString('disponivel_empresas');

        $validator
            ->integer('aparelho_padrao')
            ->requirePresence('aparelho_padrao', 'create')
            ->notEmptyString('aparelho_padrao');

        $validator
            ->integer('resultado_multiplo_5')
            ->requirePresence('resultado_multiplo_5', 'create')
            ->notEmptyString('resultado_multiplo_5');

        $validator
            ->integer('codigo_unidade')
            ->allowEmptyString('codigo_unidade');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }
}
