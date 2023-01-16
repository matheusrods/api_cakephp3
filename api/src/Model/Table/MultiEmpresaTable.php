<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * MultiEmpresa Model
 *
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 * @property \App\Model\Table\UsuarioTable&\Cake\ORM\Association\BelongsToMany $Usuario
 *
 * @method \App\Model\Entity\MultiEmpresa get($primaryKey, $options = [])
 * @method \App\Model\Entity\MultiEmpresa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MultiEmpresa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MultiEmpresa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MultiEmpresa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MultiEmpresa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MultiEmpresa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MultiEmpresa findOrCreate($search, callable $callback = null, $options = [])
 */
class MultiEmpresaTable extends AppTable
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

        $this->setTable('multi_empresa');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Endereco', [
            'foreignKey' => 'multi_empresa_id',
            'targetForeignKey' => 'endereco_id',
            'joinTable' => 'multi_empresa_endereco'
        ]);
        $this->belongsToMany('Usuario', [
            'foreignKey' => 'multi_empresa_id',
            'targetForeignKey' => 'usuario_id',
            'joinTable' => 'usuario_multi_empresa'
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
            ->scalar('razao_social')
            ->maxLength('razao_social', 255)
            ->requirePresence('razao_social', 'create')
            ->notEmptyString('razao_social');

        $validator
            ->scalar('nome_fantasia')
            ->maxLength('nome_fantasia', 255)
            ->requirePresence('nome_fantasia', 'create')
            ->notEmptyString('nome_fantasia');

        $validator
            ->scalar('codigo_documento')
            ->maxLength('codigo_documento', 18)
            ->allowEmptyString('codigo_documento');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->requirePresence('codigo_status_multi_empresa', 'create')
            ->notEmptyString('codigo_status_multi_empresa');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('logomarca')
            ->maxLength('logomarca', 50)
            ->allowEmptyString('logomarca');

        $validator
            ->scalar('cor_menu')
            ->maxLength('cor_menu', 10)
            ->allowEmptyString('cor_menu');

        $validator
            ->scalar('hash')
            ->maxLength('hash', 32)
            ->allowEmptyString('hash');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
