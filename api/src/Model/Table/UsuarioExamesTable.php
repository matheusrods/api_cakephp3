<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioExames Model
 *
 * @method \App\Model\Entity\UsuarioExame get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioExame newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioExame[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExame|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioExame saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioExame patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExame[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExame findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioExamesTable extends AppTable
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

        $this->setTable('usuario_exames');
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
            ->integer('codigo_exames')
            ->allowEmptyString('codigo_exames');

        $validator
            ->scalar('endereco_clinica')
            ->maxLength('endereco_clinica', 255)
            ->allowEmptyString('endereco_clinica');

        $validator
            ->date('data_realizacao')
            ->allowEmptyDate('data_realizacao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    function historico_exames($condicoes = []) {


        $fields = array(
            'tipo' => '\'2\'',
            'titulo_tipo' => '\'Assistencial\'',
            'codigo' => 'UsuarioExames.codigo',
            'codigo_exame' => 'UsuarioExames.codigo_exames',
            'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'clinica' => 'UsuarioExames.endereco_clinica',
            'data_realizacao' => 'UsuarioExames.data_realizacao'
        );

        $joins = array(
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'UsuarioExames.codigo_exames = Exame.codigo'
            ),
        );

        $conditions = [];

        if(!empty($condicoes)){
            $conditions = array_merge($condicoes, $conditions);
        }

        //dados para o pedido de exame
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);

        if(!empty($limit)){
            $dados->limit($limit);
        }

        return $dados;
    }
}
