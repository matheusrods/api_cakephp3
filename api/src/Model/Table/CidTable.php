<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Cid Model
 *
 * @property \App\Model\Table\AtestadosTable&\Cake\ORM\Association\BelongsToMany $Atestados
 * @property \App\Model\Table\CnaeTable&\Cake\ORM\Association\BelongsToMany $Cnae
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 *
 * @method \App\Model\Entity\Cid get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cid newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cid[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cid|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cid saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cid[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cid findOrCreate($search, callable $callback = null, $options = [])
 */
class CidTable extends AppTable
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

        $this->setTable('cid');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Atestados', [
            'foreignKey' => 'cid_id',
            'targetForeignKey' => 'atestado_id',
            'joinTable' => 'atestados_cid'
        ]);
        $this->belongsToMany('Cnae', [
            'foreignKey' => 'cid_id',
            'targetForeignKey' => 'cnae_id',
            'joinTable' => 'cid_cnae'
        ]);
        $this->belongsToMany('Endereco', [
            'foreignKey' => 'cid_id',
            'targetForeignKey' => 'endereco_id',
            'joinTable' => 'endereco_cidade'
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
            ->maxLength('descricao', 80)
            ->allowEmptyString('descricao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->scalar('codigo_cid10')
            ->maxLength('codigo_cid10', 10)
            ->requirePresence('codigo_cid10', 'create')
            ->notEmptyString('codigo_cid10');

        return $validator;
    }

    public function obterCidAutoComplete( array $params){
        $descricao = null;
        if(isset($params['descricao'])){
            $descricao = $params['descricao'];
        }
        $fields = ['id'=>'codigo', 'nome'=>'descricao','codigo_cid10'=>'codigo_cid10'];
        return $this->find()->select($fields)->where(["descricao LIKE"=> "%{$descricao}%"])->all();

    }
}
