<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FuncionarioSetoresCargos Model
 *
 * @method \App\Model\Entity\FuncionarioSetoresCargo get($primaryKey, $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FuncionarioSetoresCargo findOrCreate($search, callable $callback = null, $options = [])
 */
class FuncionarioSetoresCargosTable extends Table
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

        $this->setTable('funcionario_setores_cargos');
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
            ->date('data_inicio')
            ->allowEmptyDate('data_inicio');

        $validator
            ->date('data_fim')
            ->allowEmptyDate('data_fim');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_cargo')
            ->allowEmptyString('codigo_cargo');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente_funcionario')
            ->requirePresence('codigo_cliente_funcionario', 'create')
            ->notEmptyString('codigo_cliente_funcionario');

        $validator
            ->integer('codigo_cliente_alocacao')
            ->allowEmptyString('codigo_cliente_alocacao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_cliente_referencia')
            ->allowEmptyString('codigo_cliente_referencia');

        return $validator;
    }

    //Retorna os exames do PCMSO aplicados para unidade + setor + cargo de alocação do funcionário
    /**
     * [retornaExamesNecessarios description]
     *
     * pega os exames da grade de pcmso do funcionario para os tipos periodico, retorno ao trabalho e mudança de função
     *
     * @param  [type] $codigo_funcionario_setor_cargo [description]
     * @return [type]                                 [description]
     */
    public function retornaExamesNecessarios($codigo_funcionario_setor_cargo)
    {

        $fields = array(
            'codigo_cliente' => 'Cliente.codigo',
            'razao_social' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)',
            'nome_fantasia' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
            'exame' => 'RHHealth.dbo.ufn_decode_utf8_string(Exame.descricao)',
            'codigo_servico' => 'Exame.codigo_servico',
            'codigo_exame' => 'Exame.codigo',
            'data_nascimento' => 'Funcionario.data_nascimento',
            'codigo_tipo_exame' => 'AplicacaoExame.codigo_tipo_exame',

            'codigo_aplicacao_exame' => 'AplicacaoExame.codigo', //todo: remover
            'exame_admissional' => 'AplicacaoExame.exame_admissional',
            'exame_periodico' => 'AplicacaoExame.exame_periodico',
            'exame_demissional' => 'AplicacaoExame.exame_demissional',
            'exame_retorno' => 'AplicacaoExame.exame_retorno',
            'exame_mudanca' => 'AplicacaoExame.exame_mudanca',

            'periodo_apos_demissao' => 'AplicacaoExame.periodo_apos_demissao',
            'periodo_idade' => 'AplicacaoExame.periodo_idade',
            'periodo_idade_2' => 'AplicacaoExame.periodo_idade_2',
            'periodo_idade_3' => 'AplicacaoExame.periodo_idade_3',
            'periodo_idade_4' => 'AplicacaoExame.periodo_idade_4',
        );

        $joins = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo = FuncionarioSetoresCargos.codigo_cliente_funcionario',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'Cliente.codigo = FuncionarioSetoresCargos.codigo_cliente_alocacao',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'Funcionario.codigo = ClienteFuncionario.codigo_funcionario',
            ),
            array(
                'table' => 'aplicacao_exames',
                'alias' => 'AplicacaoExame',
                'type' => 'INNER',
                'conditions' => 'AplicacaoExame.codigo_cliente_alocacao = FuncionarioSetoresCargos.codigo_cliente_alocacao
                    AND AplicacaoExame.codigo_setor = FuncionarioSetoresCargos.codigo_setor
                    AND AplicacaoExame.codigo_cargo = FuncionarioSetoresCargos.codigo_cargo
                    AND (AplicacaoExame.codigo_funcionario = Funcionario.codigo OR AplicacaoExame.codigo_funcionario IS NULL)
                    AND AplicacaoExame.codigo IN (select * from RHHealth.dbo.ufn_aplicacao_exames(FuncionarioSetoresCargos.codigo_cliente_alocacao,FuncionarioSetoresCargos.codigo_setor,FuncionarioSetoresCargos.codigo_cargo,ClienteFuncionario.codigo_funcionario))',
            ),
            array(
                'table' => 'exames',
                'alias' => 'Exame',
                'type' => 'INNER',
                'conditions' => 'Exame.codigo = AplicacaoExame.codigo_exame',
            ),
            array(
                'table' => 'servico',
                'alias' => 'Servico',
                'type' => 'INNER',
                'conditions' => 'Servico.codigo = Exame.codigo_servico',
            )
        );

        $conditions = array(
            "FuncionarioSetoresCargos.codigo = {$codigo_funcionario_setor_cargo}",
            "Exame.ativo = 1",
            // "(AplicacaoExame.exame_periodico = 1 OR AplicacaoExame.exame_retorno = 1 OR AplicacaoExame.exame_mudanca = 1)"
            //"AplicacaoExame.exame_periodico = 1"
        );

        $dados = $this->find()
                      ->select($fields)
                      ->join($joins)
                      ->where($conditions)
                      ->hydrate(false)
                      ->toArray();
        // debug($dados->sql());exit;

        return $dados;

    }//fim

    public function getFuncionariosExpostos($codigo_cargo,$codigo_gestor)
    {
        $fields = array(
            'codigo' => 'usuario.codigo',
            'nome' => 'RHHealth.dbo.ufn_decode_utf8_string(usuario.nome)',

        );

        $joins = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionario.codigo',
            ),
            array(
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'INNER',
                'conditions' => 'Funcionario.cpf = usuario.apelido',
            )
        );

        $conditions = array(
            "FuncionarioSetoresCargos.data_fim is null",
            "FuncionarioSetoresCargos.codigo_cargo" => $codigo_cargo,
            "usuario.codigo_gestor" => $codigo_gestor
        );


        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->hydrate(false)
            ->toArray();

        return $dados;
    }
}
