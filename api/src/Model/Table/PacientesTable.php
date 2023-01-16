<?php
namespace App\Model\Table;

use App\Utils\Comum;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pacientes Model
 *
 * @method \App\Model\Entity\Paciente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Paciente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Paciente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Paciente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Paciente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Paciente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Paciente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Paciente findOrCreate($search, callable $callback = null, $options = [])
 */
class PacientesTable extends Table
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

        $this->setTable('pacientes');
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
            ->scalar('nome')
            ->maxLength('nome', 60)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 11)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('rg')
            ->maxLength('rg', 10)
            ->allowEmptyString('rg');

        $validator
            ->dateTime('data_nascimento')
            ->requirePresence('data_nascimento', 'create')
            ->notEmptyDateTime('data_nascimento');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 15)
            ->requirePresence('telefone', 'create')
            ->notEmptyString('telefone');

        $validator
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->allowEmptyString('sexo');

        return $validator;
    }

    public function getPacienteCpf(string $cpf)
    {
        $cpf = Comum::soNumero($cpf);
        $query = "SELECT * FROM pacientes WHERE cpf = '{$cpf}'";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return $dados;
    }

    public function getExamePacienteTerceiro()
    {
        $query = "  select e.codigo, RHHealth.dbo.ufn_decode_utf8_string(e.descricao)  as descricao
                    from listas_de_preco lp
                     inner join listas_de_preco_produto lpp on lp.codigo = lpp.codigo_lista_de_preco
                     inner join listas_de_preco_produto_servico lpps on lpp.codigo = lpps.codigo_lista_de_preco_produto
                     inner join exames e on e.codigo_servico = lpps.codigo_servico
                    where lp.codigo_fornecedor = 8360
                     and lpp.codigo_produto = 59
                     and e.ativo = 1;";

        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        //print_r($query);
        return $dados;
    }


}
