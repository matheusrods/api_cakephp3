<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\Datasource\ConnectionManager;

/**
 * ClienteFuncionario Model
 *
 * @method \App\Model\Entity\ClienteFuncionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteFuncionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteFuncionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteFuncionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteFuncionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteFuncionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteFuncionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteFuncionario findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteFuncionarioTable extends AppTable
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

        $this->setTable('cliente_funcionario');
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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_funcionario')
            ->requirePresence('codigo_funcionario', 'create')
            ->notEmptyString('codigo_funcionario');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_cargo')
            ->allowEmptyString('codigo_cargo');

        $validator
            ->date('admissao')
            ->allowEmptyDate('admissao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('matricula')
            ->maxLength('matricula', 11)
            ->allowEmptyString('matricula');

        $validator
            ->date('data_demissao')
            ->allowEmptyDate('data_demissao');

        $validator
            ->scalar('centro_custo')
            ->maxLength('centro_custo', 256)
            ->allowEmptyString('centro_custo');

        $validator
            ->date('data_ultima_aso')
            ->allowEmptyDate('data_ultima_aso');

        $validator
            ->integer('aptidao')
            ->allowEmptyString('aptidao');

        $validator
            ->integer('turno')
            ->allowEmptyString('turno');

        $validator
            ->integer('codigo_cliente_matricula')
            ->allowEmptyString('codigo_cliente_matricula');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->allowEmptyString('matricula_candidato');

        return $validator;
    }

    /**
     * [getFuncionarioFuncao description]
     * 
     * metodo para pegar as matriculas e funcoes do funcionario
     * 
     * @param  [type] $codigo_funcionario [description]
     * @return [type]                     [description]
     */
    public function getFuncionarioFuncao($codigo_funcionario, $codigos_clientes)
    {

        // debug(array($codigo_funcionario,$codigos_clientes));exit;

        //verifica se o codigo_funcionario
        if (!$codigo_funcionario) {
            return false;
        }

        //campos
        $fields = array(
            'ClienteFuncionario.codigo',
            'ClienteFuncionario.codigo_cliente_matricula',
            'ClienteFuncionario.ativo',
            'ClienteFuncionario.admissao',
            'ClienteFuncionario.data_demissao',
            'ClienteFuncionario.codigo_empresa',
            'Cliente.codigo',
            'Cliente.nome_fantasia',
            'Cliente.razao_social',
            'FuncionarioSetorCargo.codigo',
            'FuncionarioSetorCargo.data_inicio',
            'FuncionarioSetorCargo.data_fim',
            'FuncionarioSetorCargo.codigo_cliente_alocacao',
            'FuncionarioSetorCargo.codigo_setor',
            'FuncionarioSetorCargo.codigo_cargo',
            'Funcionario.codigo',
            'Funcionario.cpf',
            'Funcionario.data_nascimento',
        );

        //monta os relacionamentos
        $joins = array(
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'Funcionario.codigo = ClienteFuncionario.codigo_funcionario'
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetorCargo',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetorCargo.codigo_cliente_funcionario = ClienteFuncionario.codigo'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao'
            ),
        );

        //monta o filtro pelo codigo do funcionario
        $conditions = array(
            'ClienteFuncionario.codigo_funcionario' => $codigo_funcionario,
            //'ClienteFuncionario.ativo <> 0',
            'FuncionarioSetorCargo.data_fim IS NULL',
            'OR' => array(
                'ClienteFuncionario.codigo_cliente_matricula IN ' => $codigos_clientes,
                'FuncionarioSetorCargo.codigo_cliente_alocacao IN ' => $codigos_clientes
            )
        );

        //monta a execucao
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false);

        // debug($dados->sql());exit;

        return $dados->toArray();
    } //fim getFuncionarioFuncao

    public function conciliarDuplicatasClienteDs($codigoClienteDsConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_ds IN' => $arrCodigosDuplicatas])
                ->update()
                ->set(['codigo_cliente_ds' => $codigoClienteDsConciliador])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    public function conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_bu IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_bu' => $codigoClienteBuConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    public function conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_opco IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_opco' => $codigoClienteOpcoConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
