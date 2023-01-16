<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\FuncionariosTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\Comum;
use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;

/**
 * ThermalFuncionariosTable
 * 
 * Operações com funcionários relativas ao aplicativo Thermal-Care
 * 
 */
class ThermalFuncionariosTable extends FuncionariosTable
{
    // Alias ThermalFuncionarios
    // table funcionarios

    /**
     * Obtém funcionários em uma determinada Matriz
     * 
     * Este é um método principal com as regras para retornar funcionários, deve ser reutilizado com incremento de
     * - $fields
     * - $conditions
     *
     * @param integer $codigo_cliente_matriz
     * @param array $fields
     * @param array $conditions
     * @return Cake/ORM/Query
     */
    public function obterFuncionarios(int $codigo_cliente_matriz, array $fields = [], array $conditions = []) {
            
        if(empty($codigo_cliente_matriz)){
            throw new Exception("Obrigatório o parâmetro código_cliente_matriz", 1);
        }

        if(empty($fields))
        {
            $fields = [
                'codigo'  => 'ThermalFuncionarios.codigo',
                'cpf'     => 'ThermalFuncionarios.cpf',
                'nome'    => 'ThermalFuncionarios.nome',
                'sexo'    => 'ThermalFuncionarios.sexo',
                'rg'    => 'ThermalFuncionarios.rg',
                'email'    => 'ThermalFuncionarios.email',
                'foto'    => 'ThermalFuncionarios.foto',
                'matricula'    => 'ClienteFuncionario.matricula',
                'data_nascimento' => 'ThermalFuncionarios.data_nascimento',
                'empresa' => 'GruposEconomicos.descricao',
                'setor'   => 'Setores.descricao',
                'cargo'   => 'cargos.descricao',
                'tipo'    => "'funcionario'",
                'codigo_cliente' => 'ClienteFuncionario.codigo_cliente',
                'codigo_funcionario_setor_cargo' => 'FuncionariosSetoresCargos.codigo',
                'codigo_cliente_funcionario' => 'ClienteFuncionario.codigo'
            ];
        }

        $joins  = [
            [
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type'  => 'INNER',
                'conditions' => 'ThermalFuncionarios.codigo = ClienteFuncionario.codigo_funcionario',
            ],
            [
                'table' => 'grupos_economicos',
                'alias' => 'GruposEconomicos',
                'type'  => 'INNER',
                'conditions' => 'GruposEconomicos.codigo_cliente = ClienteFuncionario.codigo_cliente',
            ],
            [
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionariosSetoresCargos',
                'type'  => 'INNER',
                'conditions' => 'FuncionariosSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionariosSetoresCargos.data_fim IS NULL',
            ],
            [
                'table' => 'cargos',
                'alias' => 'Cargos',
                'type'  => 'INNER',
                'conditions' => 'Cargos.codigo = FuncionariosSetoresCargos.codigo_cargo',
            ],
            [
                'table' => 'setores',
                'alias' => 'Setores',
                'type'  => 'INNER',
                'conditions' => 'Setores.codigo = FuncionariosSetoresCargos.codigo_setor'
            ],
            [
                'table' => 'cliente',
                'alias' => 'ClienteMatriz',
                'type' => 'INNER',
                'conditions' => 'ClienteMatriz.codigo = ClienteFuncionario.codigo_cliente_matricula',
            ],
        ];

        // Condições para retornar os dados dos funcionários
        if(!isset($conditions["ClienteFuncionario.codigo_cliente_matricula"]))
        {
            $conditions["ClienteFuncionario.codigo_cliente_matricula"] = $codigo_cliente_matriz;    
        }

        return $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions);
    }

    /**
     * Obtém uma lista de funcionários pesquisados por CPF
     *
     * @param integer $cpf
     * @param integer $codigo_cliente_matriz
     * @param boolean $likeCondition
     * @return Cake/ORM/Query
     */
    public function obterFuncionarioPorCpf($cpf, $codigo_cliente_matriz = null, $likeCondition = false)
    {
        if($likeCondition){
            $conditions = ["ThermalFuncionarios.cpf LIKE" => "{$cpf}%"];
        } else {
            $conditions = ["ThermalFuncionarios.cpf" => $cpf];
        }

        return $this->obterFuncionarios( $codigo_cliente_matriz, [], $conditions);
    }

    /**
     * Obtém uma lista de funcionários pesquisados por Nome
     *
     * @param string $nome
     * @param integer $codigo_cliente_matriz
     * @param boolean $likeCondition
     * @return Cake/ORM/Query
     */
    public function obterFuncionarioPorNome($nome, $codigo_cliente_matriz = null, $likeCondition = false)
    {

        if($likeCondition){
            $conditions = ["ThermalFuncionarios.nome LIKE" => "{$nome}%"];
        } else {
            $conditions = ["ThermalFuncionarios.nome " => "{$nome}"];
        }

        return $this->obterFuncionarios( $codigo_cliente_matriz, [], $conditions);
    }

}
