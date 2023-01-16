<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use Cake\Datasource\ConnectionManager;

/**
 * FuncionariosContatos Model
 *
 * @method \App\Model\Entity\FuncionariosContato get($primaryKey, $options = [])
 * @method \App\Model\Entity\FuncionariosContato newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FuncionariosContato[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FuncionariosContato|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionariosContato saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionariosContato patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionariosContato[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionariosContato findOrCreate($search, callable $callback = null, $options = [])
 */
class FuncionariosContatosTable extends AppTable
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

        $this->setTable('funcionarios_contatos');
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
            ->integer('codigo_funcionario')
            ->requirePresence('codigo_funcionario', 'create')
            ->notEmptyString('codigo_funcionario');

        $validator
            ->requirePresence('codigo_tipo_contato', 'create')
            ->notEmptyString('codigo_tipo_contato');

        $validator
            ->requirePresence('codigo_tipo_retorno', 'create')
            ->notEmptyString('codigo_tipo_retorno');

        $validator
            ->allowEmptyString('ddi');

        $validator
            ->allowEmptyString('ddd');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 256)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 256)
            ->allowEmptyString('nome');

        $validator
            ->allowEmptyString('ramal');

        $validator
            ->integer('autoriza_envio_sms')
            ->allowEmptyString('autoriza_envio_sms');

        $validator
            ->integer('autoriza_envio_email')
            ->allowEmptyString('autoriza_envio_email');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getContatosByCodigo($codigo)
    {

        $query = "SELECT
                    fc.codigo
                , fc.codigo_tipo_contato
                , tc.descricao descricao_tipo_contato
                , fc.codigo_tipo_retorno
                , tr.descricao descricao_tipo_retorno
                , fc.ddi
                , fc.ddd
                , fc.descricao
                , fc.data_inclusao
                , fc.data_alteracao
            FROM dbo.funcionarios_contatos fc
               INNER JOIN dbo.tipo_contato tc ON tc.codigo = fc.codigo_tipo_contato
                INNER JOIN dbo.tipo_retorno tr ON tr.codigo = fc.codigo_tipo_retorno
            WHERE fc.codigo_funcionario = {$codigo}
            ORDER BY fc.codigo DESC";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }
}
