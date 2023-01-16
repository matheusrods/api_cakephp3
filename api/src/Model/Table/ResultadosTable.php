<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Resultados Model
 *
 * @property \App\Model\Table\AparelhosAudiometricosTable&\Cake\ORM\Association\BelongsToMany $AparelhosAudiometricos
 *
 * @method \App\Model\Entity\Resultado get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resultado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resultado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resultado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resultado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resultado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resultado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resultado findOrCreate($search, callable $callback = null, $options = [])
 */
class ResultadosTable extends AppTable
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

        $this->setTable('resultados');

        $this->belongsToMany('AparelhosAudiometricos', [
            'foreignKey' => 'resultado_id',
            'targetForeignKey' => 'aparelhos_audiometrico_id',
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
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 500)
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->integer('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->requirePresence('codigo_usuario_alteracao', 'create')
            ->notEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        return $validator;
    }
}
