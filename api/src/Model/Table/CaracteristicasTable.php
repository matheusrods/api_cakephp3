<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Caracteristicas Model
 *
 * @property \App\Model\Table\QuestionariosTable&\Cake\ORM\Association\BelongsToMany $Questionarios
 * @property \App\Model\Table\QuestoesTable&\Cake\ORM\Association\BelongsToMany $Questoes
 * @property \App\Model\Table\SetoresTable&\Cake\ORM\Association\BelongsToMany $Setores
 *
 * @method \App\Model\Entity\Caracteristica get($primaryKey, $options = [])
 * @method \App\Model\Entity\Caracteristica newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Caracteristica[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Caracteristica|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Caracteristica saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Caracteristica patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Caracteristica[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Caracteristica findOrCreate($search, callable $callback = null, $options = [])
 */
class CaracteristicasTable extends AppTable
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

        $this->setTable('caracteristicas');

        $this->belongsToMany('Questionarios', [
            'foreignKey' => 'caracteristica_id',
            'targetForeignKey' => 'questionario_id',
            'joinTable' => 'caracteristicas_questionarios'
        ]);
        $this->belongsToMany('Questoes', [
            'foreignKey' => 'caracteristica_id',
            'targetForeignKey' => 'questo_id',
            'joinTable' => 'caracteristicas_questoes'
        ]);
        $this->belongsToMany('Setores', [
            'foreignKey' => 'caracteristica_id',
            'targetForeignKey' => 'setore_id',
            'joinTable' => 'setores_caracteristicas'
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
            ->scalar('titulo')
            ->maxLength('titulo', 200)
            ->allowEmptyString('titulo');

        $validator
            ->scalar('alerta')
            ->maxLength('alerta', 200)
            ->allowEmptyString('alerta');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }
}
