<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * MotivosAfastamento Model
 *
 * @method \App\Model\Entity\MotivosAfastamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\MotivosAfastamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\MotivosAfastamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\MotivosAfastamento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MotivosAfastamento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\MotivosAfastamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\MotivosAfastamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\MotivosAfastamento findOrCreate($search, callable $callback = null, $options = [])
 */
class MotivosAfastamentoTable extends AppTable
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

        $this->setTable('motivos_afastamento');
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
            ->scalar('descricao')
            ->maxLength('descricao', 100)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->integer('codigo_tipo_afastamento')
            ->allowEmptyString('codigo_tipo_afastamento');

        $validator
            ->integer('codigo_esocial')
            ->allowEmptyString('codigo_esocial');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function obterLista($codigo_tipo_afastamento=null){


        $dados = $this->find()
                    ->select(['codigo','descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])
                    ->where(['ativo' => 1, 'codigo_esocial IS NOT NULL'])
                    ->order(['descricao ASC']);
                    
        if(!empty($codigo_tipo_afastamento)) {
            $dados->where(['codigo_tipo_afastamento' => $codigo_tipo_afastamento]);
        }

        // debug($dados->sql());exit;

        return $dados;
    }
    
}
