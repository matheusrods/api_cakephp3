<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Auth Model
 *
 * @method \App\Model\Entity\Auth get($primaryKey, $options = [])
 * @method \App\Model\Entity\Auth|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 *
 */
class AuthTable extends AppTable
{

    public static function defaultConnectionName()
    {
        return 'default';
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('usuario');
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
            ->scalar('apelido')
            ->maxLength('apelido', 256)
            ->requirePresence('apelido', 'create')
            ->notEmptyString('apelido');

        $validator
            ->scalar('senha')
            ->maxLength('senha', 172)
            ->allowEmptyString('senha');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');


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

    public function atualizaSenha($codigo_usuario, $params){

        $registro = $this->get(['codigo' => $codigo_usuario]);

        if(!$registro){
            $error = 'Registro não encontrado.';
            return ['error' => $error];
        }

        if (strlen($params['senha_atual']) < 8) {
            $error = 'Senha precisa ter ao menos 8 caracteres.';
            return ['error' => $error];
        }

        $params = [
            'senha' => $params['senha_atual']
        ];

        $registroEntity = $this->patchEntity($registro, $params);

        if (!$this->save($registroEntity)) {
            $error = $registro->getValidationErrors();
            return ['error' => $error];
        }

        return true;
    }

}
