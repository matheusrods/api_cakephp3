<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Onboarding Model
 *
 * @property \App\Model\Table\ClienteTable&\Cake\ORM\Association\BelongsToMany $Cliente
 * @property \App\Model\Table\ClienteLogTable&\Cake\ORM\Association\BelongsToMany $ClienteLog
 *
 * @method \App\Model\Entity\Onboarding get($primaryKey, $options = [])
 * @method \App\Model\Entity\Onboarding newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Onboarding[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Onboarding|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Onboarding saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Onboarding patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Onboarding[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Onboarding findOrCreate($search, callable $callback = null, $options = [])
 */
class OnboardingTable extends AppTable
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

        $this->setTable('onboarding');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Cliente', [
            'foreignKey' => 'onboarding_id',
            'targetForeignKey' => 'cliente_id',
            'joinTable' => 'onboarding_cliente',
        ]);
        $this->belongsToMany('ClienteLog', [
            'foreignKey' => 'onboarding_id',
            'targetForeignKey' => 'cliente_log_id',
            'joinTable' => 'onboarding_cliente_log',
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
            ->integer('codigo_sistema')
            ->requirePresence('codigo_sistema', 'create')
            ->notEmptyString('codigo_sistema');

        $validator
            ->scalar('titulo')
            ->maxLength('titulo', 255)
            ->requirePresence('titulo', 'create')
            ->notEmptyString('titulo');

        $validator
            ->scalar('texto')
            ->maxLength('texto', 255)
            ->requirePresence('texto', 'create')
            ->notEmptyString('texto');

        $validator
            ->scalar('imagem')
            ->allowEmptyFile('imagem');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    /**
     * Avaliar lista de onboarding 
     *
     * @param int $codigo_sistema
     * @param boolean $inativos   se true irá trazer registros inativos
     * @return ORM Recordset
     */
    public function obterLista(int $codigo_sistema, $inativos = false)
    {
       
        $where = [];

        if(!$inativos){
            array_push($where, ['Onboarding.ativo'=> 1]);
        }
        
        array_push($where, [
            'Onboarding.codigo_sistema' => $codigo_sistema
        ]);

        return $this->find()->where($where);

    }

}
