<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioContatoEmergencia Model
 *
 * @method \App\Model\Entity\UsuarioContatoEmergencium get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioContatoEmergencium findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioContatoEmergenciaTable extends AppTable
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

        $this->setTable('usuario_contato_emergencia');
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
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');
        
        // $validator
        //     ->email('email')
        //     ->allowEmptyString('email');

        // $validator
        //     ->scalar('telefone')
        //     ->maxLength('telefone', 172)
        //     ->allowEmptyString('telefone');

        // $validator
        //     ->dateTime('celular')
        //     ->allowEmptyDateTime('celular');

        // $validator
        //     ->integer('ativo')
        //     ->allowEmptyString('ativo');

        // $validator
        //     ->integer('codigo_usuario_inclusao')
        //     ->allowEmptyString('codigo_usuario_inclusao');

        // $validator
        //     ->dateTime('data_inclusao')
        //     ->requirePresence('data_inclusao', 'create')
        //     ->notEmptyDateTime('data_inclusao');

        // $validator
        //     ->integer('codigo_usuario_alteracao')
        //     ->allowEmptyString('codigo_usuario_alteracao');

        // $validator
        //     ->dateTime('data_alteracao')
        //     ->requirePresence('data_alteracao', 'create')
        //     ->notEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * Salvar/atualizar um contato de emergencia
     *
     * @param integer $codigo_usuario
     * @param array $params
     * @return void
     */
    public function salvarContatoEmergencium(int $codigo_usuario, array $params = []){

        //verifica se existe contato de emergencia
        $conditions = ['codigo_usuario' => $codigo_usuario];

        $usuarioContatoEmergencium = $this->find()->where($conditions)->first();
        
        if(empty($usuarioContatoEmergencium)) {
            $entity = $this->newEntity($params);  
            $entity->set('codigo_usuario', $codigo_usuario);
            $entity->set('codigo_usuario_inclusao', $codigo_usuario);
        } else {
            $r = $this->get($usuarioContatoEmergencium->codigo);
            $entity = $this->patchEntity($r, $params);
            $entity->set('codigo_usuario_alteracao', $codigo_usuario);
        }

        if(isset($params['nome'])){
            $entity->set('nome', $params['nome']);
        }
        if(isset($params['email'])){
            $entity->set('email', $params['email']);
        }
        if(isset($params['grau_parentesco'])){
            $entity->set('grau_parentesco', $params['grau_parentesco']);
        }
        
        if (!$this->save($entity)) {
            return ['error'=>$entity->getValidationErrors()];
        }

        return "Contato de Emergencia atualizado com Sucesso!";
    }
}
