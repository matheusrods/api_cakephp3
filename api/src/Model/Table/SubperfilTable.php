<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Subperfil Model
 *
 * @property \App\Model\Table\AcoesTable&\Cake\ORM\Association\BelongsToMany $Acoes
 * @property \App\Model\Table\UsuarioTable&\Cake\ORM\Association\BelongsToMany $Usuario
 *
 * @method \App\Model\Entity\Subperfil get($primaryKey, $options = [])
 * @method \App\Model\Entity\Subperfil newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Subperfil[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Subperfil|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subperfil saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subperfil patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Subperfil[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Subperfil findOrCreate($search, callable $callback = null, $options = [])
 */
class SubperfilTable extends Table
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

        $this->setTable('subperfil');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Acoes', [
            'foreignKey' => 'subperfil_id',
            'targetForeignKey' => 'aco_id',
            'joinTable' => 'subperfil_acoes',
        ]);
        $this->belongsToMany('Usuario', [
            'foreignKey' => 'subperfil_id',
            'targetForeignKey' => 'usuario_id',
            'joinTable' => 'usuario_subperfil',
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

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
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->boolean('interno')
            ->allowEmptyString('interno');

        return $validator;
    }

    public function getPermissoesUsuario($codigo_usuario)
    {

        $fields = array(
            'codigo_acao' => 'Acoes.codigo',
            'acao' => 'Acoes.descricao'
        );

        $joins = array(
            array(
                'table' => 'usuario_subperfil',
                'alias' => 'UsuarioSubperfil',
                'type' => 'INNER',
                'conditions' => "UsuarioSubperfil.codigo_usuario = {$codigo_usuario} AND Subperfil.codigo = UsuarioSubperfil.codigo_subperfil",
            ),
            array(
                'table' => 'subperfil_acoes',
                'alias' => 'SubperfilAcoes',
                'type' => 'INNER',
                'conditions' => "SubperfilAcoes.codigo_subperfil = Subperfil.codigo",
            ),
            array(
                'table' => 'acoes',
                'alias' => 'Acoes',
                'type' => 'INNER',
                'conditions' => "SubperfilAcoes.codigo_acao = Acoes.codigo",
            ),
        );

        $conditions[] = array(
            "UsuarioSubperfil.codigo_usuario = {$codigo_usuario}"
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        return array_values(array_unique($dados, SORT_REGULAR));
    }

    public function getByCodigoUsuario($codigo_usuario) {
        
        $query = $this->find()
            ->select([
                'Subperfil.codigo',
                'Subperfil.descricao'
            ])
            ->join(
                [
                    'table' => 'usuario_subperfil',
                    'alias' => 'UsuarioSubperfil',
                    'type' => 'INNER',
                    'conditions' => 'Subperfil.codigo = UsuarioSubperfil.codigo_subperfil',
                ],                 
                [
                    'table' => 'usuario',
                    'alias' => 'Usuario',
                    'type' => 'INNER',
                    'conditions' => 'Usuario.codigo = SubperfilUsuario.codigo_usuario',
                ],                
            )
            ->where([
                'UsuarioSubperfil.codigo_usuario' => $codigo_usuario,
            ]);            

        return $query->all()
                ->toArray();
    }
}
