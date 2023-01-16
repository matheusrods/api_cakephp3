<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Questoes Model
 *
 * @property \App\Model\Table\CaracteristicasTable&\Cake\ORM\Association\BelongsToMany $Caracteristicas
 * @property \App\Model\Table\FichasAssistenciaisTable&\Cake\ORM\Association\BelongsToMany $FichasAssistenciais
 * @property \App\Model\Table\FichasClinicasTable&\Cake\ORM\Association\BelongsToMany $FichasClinicas
 *
 * @method \App\Model\Entity\Questoes get($primaryKey, $options = [])
 * @method \App\Model\Entity\Questoes newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Questoes[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Questoes|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Questoes saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Questoes patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Questoes[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Questoes findOrCreate($search, callable $callback = null, $options = [])
 */
class QuestoesTable extends AppTable
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

        $this->setTable('questoes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Caracteristicas', [
            'foreignKey' => 'questo_id',
            'targetForeignKey' => 'caracteristica_id',
            'joinTable' => 'caracteristicas_questoes'
        ]);
        $this->belongsToMany('FichasAssistenciais', [
            'foreignKey' => 'questo_id',
            'targetForeignKey' => 'fichas_assistenciai_id',
            'joinTable' => 'fichas_assistenciais_questoes'
        ]);
        $this->belongsToMany('FichasClinicas', [
            'foreignKey' => 'questo_id',
            'targetForeignKey' => 'fichas_clinica_id',
            'joinTable' => 'fichas_clinicas_questoes'
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
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->integer('ordem')
            ->allowEmptyString('ordem');

        $validator
            ->notEmptyString('status');

        $validator
            ->integer('codigo_proxima_questao')
            ->allowEmptyString('codigo_proxima_questao');

        $validator
            ->scalar('label')
            ->maxLength('label', 500)
            ->allowEmptyString('label');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 25)
            ->allowEmptyString('tipo');

        $validator
            ->scalar('observacoes')
            ->maxLength('observacoes', 500)
            ->allowEmptyString('observacoes');

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
            ->integer('codigo_questao')
            ->allowEmptyString('codigo_questao');

        $validator
            ->integer('codigo_label_questao')
            ->allowEmptyString('codigo_label_questao');

        $validator
            ->integer('pontos')
            ->allowEmptyString('pontos');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        return $validator;
    }
}
