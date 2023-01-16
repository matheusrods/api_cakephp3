<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsuarioFuncao Model
 *
 * @method \App\Model\Entity\UsuarioFuncao get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioFuncao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioFuncao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFuncao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioFuncao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioFuncao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFuncao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioFuncao findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioFuncaoTable extends Table
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

        $this->setTable('usuario_funcao');
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
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_funcao_tipo')
            ->requirePresence('codigo_funcao_tipo', 'create')
            ->notEmptyString('codigo_funcao_tipo');

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
            ->integer('ativo')
            ->allowEmptyString('ativo');

        return $validator;
    }

    public function getUsuarioTecnicoEHS($codigo_cliente)
    {
        //campos do select
        $fields = array(
            'codigo_usuario' => 'usuario.codigo',
            'nome' => 'usuario.nome',
            'cpf' => 'UsuariosDados.cpf',
            'codigo_cliente' => 'FuncionarioSetoresCargos.codigo_cliente',
            'codigo_setor' => 'FuncionarioSetoresCargos.codigo_setor',
            'codigo_cargo' => 'FuncionarioSetoresCargos.codigo_cargo',
            'codigo_funcao_tipo' => 'UsuarioFuncao.codigo_funcao_tipo',
            'ativo' => 'UsuarioFuncao.ativo'
        );

        $joins = [
            [
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'INNER',
                'conditions' => 'usuario.ativo = 1'
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = usuario.codigo'
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf'
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo'
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetoresCargos',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionarioSetoresCargos.data_fim is NULL'
            ]
        ];

        $conditions = "UsuarioFuncao.codigo_usuario = usuario.codigo AND UsuarioFuncao.codigo_funcao_tipo = 3 AND UsuarioFuncao.ativo = 1
        AND FuncionarioSetoresCargos.codigo_cliente = " . $codigo_cliente . " ";

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->toArray();

        $this->utf8DecodeUsuariosNomes($dados);

        return $dados;
    }

    public function getUsuarioGestor($codigo_cliente)
    {
        //campos do select
        $fields = array(
            'codigo_usuario' => 'usuario.codigo',
            'nome' => 'usuario.nome',
            'cpf' => 'usuario.apelido',
            'codigo_cliente' => 'FuncionarioSetoresCargos.codigo_cliente',
            //            'codigo_setor' => 'FuncionarioSetoresCargos.codigo_setor',
            //            'codigo_cargo' => 'FuncionarioSetoresCargos.codigo_cargo',
            'codigo_funcao_tipo' => 'UsuarioFuncao.codigo_funcao_tipo',
            'ativo' => 'UsuarioFuncao.ativo',
        );

        $joins = [
            [
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'INNER',
                'conditions' => 'usuario.ativo = 1'
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.codigo_usuario = usuario.codigo'
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'usuario.apelido = (CASE WHEN UsuariosDados.cpf IS NULL THEN usuario.apelido ELSE UsuariosDados.cpf END) '
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo'
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetoresCargos',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionarioSetoresCargos.data_fim is NULL'
            ]
        ];

        $conditions = "UsuarioFuncao.codigo_usuario = usuario.codigo AND UsuarioFuncao.codigo_funcao_tipo = 4 AND UsuarioFuncao.ativo = 1
        AND FuncionarioSetoresCargos.codigo_cliente = " . $codigo_cliente . " ";

        $group = array(
            'usuario.codigo',
            'usuario.nome',
            'usuario.apelido',
            'FuncionarioSetoresCargos.codigo_cliente',
            //            'codigo_setor' => 'FuncionarioSetoresCargos.codigo_setor',
            //            'codigo_cargo' => 'FuncionarioSetoresCargos.codigo_cargo',
            'UsuarioFuncao.codigo_funcao_tipo',
            'UsuarioFuncao.ativo',
        );

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->group($group)
            ->toArray();


        $this->utf8DecodeUsuariosNomes($dados);

        return $dados;
    }

    public function getUsuarioCliente($codigo_cliente)
    {
        //campos do select
        $fields = array(
            'codigo_usuario' => 'usuario.codigo',
            'nome' => 'usuario.nome',
            'cpf' => 'UsuariosDados.cpf',
            'codigo_cliente' => 'FuncionarioSetoresCargos.codigo_cliente',
            'codigo_setor' => 'FuncionarioSetoresCargos.codigo_setor',
            'codigo_cargo' => 'FuncionarioSetoresCargos.codigo_cargo',
            'codigo_funcao_tipo' => 'UsuarioFuncao.codigo_funcao_tipo',
            'ativo' => 'UsuarioFuncao.ativo'
        );

        $joins = [
            [
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'INNER',
                'conditions' => 'usuario.ativo = 1'
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = usuario.codigo'
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf'
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo'
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetoresCargos',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionarioSetoresCargos.data_fim is NULL'
            ]
        ];

        $conditions = "UsuarioFuncao.codigo_usuario = usuario.codigo AND UsuarioFuncao.ativo = 1 AND FuncionarioSetoresCargos.codigo_cliente = " . $codigo_cliente . " ";

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->toArray();

        $this->utf8DecodeUsuariosNomes($dados);

        return $dados;
    }

    public function getUsuarioMeuTime($codigo_usuario)
    {
        //campos do select
        $fields = array(
            'codigo_usuario' => 'usuario.codigo',
            'nome' => 'usuario.nome',
            'cpf' => 'UsuariosDados.cpf',
            'codigo_cliente' => 'FuncionarioSetoresCargos.codigo_cliente',
            'codigo_setor' => 'FuncionarioSetoresCargos.codigo_setor',
            'codigo_cargo' => 'FuncionarioSetoresCargos.codigo_cargo',
            'codigo_funcao_tipo' => 'UsuarioFuncao.codigo_funcao_tipo',
            'codigo_gestor' => 'usuario.codigo_gestor',
            'ativo' => 'UsuarioFuncao.ativo'
        );

        $joins = [
            [
                'table' => 'usuario',
                'alias' => 'usuario',
                'type' => 'INNER',
                'conditions' => 'usuario.ativo = 1 AND usuario.codigo_gestor = ' . $codigo_usuario . ' '
            ],
            [
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = usuario.codigo'
            ],
            [
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf'
            ],
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_funcionario = Funcionarios.codigo'
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionarioSetoresCargos',
                'type' => 'INNER',
                'conditions' => 'FuncionarioSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionarioSetoresCargos.data_fim is NULL'
            ]
        ];

        $conditions = "UsuarioFuncao.codigo_usuario = usuario.codigo AND UsuarioFuncao.ativo = 1 AND FuncionarioSetoresCargos.codigo_cliente = 10011 ";

        //executa os dados
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->toArray();

        $this->utf8DecodeUsuariosNomes($dados);

        return $dados;
    }

    private function utf8DecodeUsuariosNomes(&$usuarios)
    {

        foreach ($usuarios as $key => $usuario) {

            $usuarios[$key]->nome = utf8_decode($usuario->nome);
        }
        return $usuarios;
    }
}
