<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * GrupoExposicao Model
 *
 * @property \App\Model\Table\RiscosAtributosDetalhesTable&\Cake\ORM\Association\BelongsToMany $RiscosAtributosDetalhes
 *
 * @method \App\Model\Entity\GrupoExposicao get($primaryKey, $options = [])
 * @method \App\Model\Entity\GrupoExposicao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\GrupoExposicao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\GrupoExposicao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GrupoExposicao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\GrupoExposicao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\GrupoExposicao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\GrupoExposicao findOrCreate($search, callable $callback = null, $options = [])
 */
class GrupoExposicaoTable extends AppTable
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

        $this->setTable('grupo_exposicao');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('RiscosAtributosDetalhes', [
            'foreignKey' => 'grupo_exposicao_id',
            'targetForeignKey' => 'riscos_atributos_detalhe_id',
            'joinTable' => 'grupo_exposicao_riscos_atributos_detalhes'
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
            ->integer('codigo_cargo')
            ->allowEmptyString('codigo_cargo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('descricao_atividade')
            ->allowEmptyString('descricao_atividade');

        $validator
            ->dateTime('data_documento')
            ->allowEmptyDateTime('data_documento');

        $validator
            ->scalar('observacao')
            ->allowEmptyString('observacao');

        $validator
            ->integer('codigo_cliente_setor')
            ->allowEmptyString('codigo_cliente_setor');

        $validator
            ->integer('codigo_grupo_homogeneo')
            ->allowEmptyString('codigo_grupo_homogeneo');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->scalar('medidas_controle')
            ->allowEmptyString('medidas_controle');

        $validator
            ->integer('funcionario_entrevistado')
            ->allowEmptyString('funcionario_entrevistado');

        $validator
            ->dateTime('data_inicio_vigencia')
            ->allowEmptyDateTime('data_inicio_vigencia');

        $validator
            ->integer('codigo_medico')
            ->allowEmptyString('codigo_medico');

        $validator
            ->scalar('funcionario_entrevistado_terceiro')
            ->maxLength('funcionario_entrevistado_terceiro', 255)
            ->allowEmptyString('funcionario_entrevistado_terceiro');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }


    /**
     * [verificaFuncionarioTemPpra description]
     * 
     * metodo para saber se o funcionario tem ppra criado no nivel de unidade/setor/cargo
     * 
     * @param  [type] $codigo_funcionario_setor_cargo [description]
     * @return [type]                                 [description]
     */
    public function verificaFuncionarioTemPpra($codigo_funcionario_setor_cargo)
    {
        //campos da grupo de exposicao
        $fields = array(
            'GrupoExposicao.codigo',
            'GrupoExposicao.codigo_cargo',
            'GrupoExposicao.codigo_funcionario'
        );

        //relacionamentos da query
        $joins = array(
            array(
                'table'         => 'clientes_setores',
                'alias'         => 'ClienteSetor',
                'type'          => 'INNER',
                'conditions'    => 'ClienteSetor.codigo = GrupoExposicao.codigo_cliente_setor'
            ),
            array(
                'table'         => 'grupos_exposicao_risco',
                'alias'         => 'GrupoExposicaoRisco',
                'type'          => 'INNER',
                'conditions'    => 'GrupoExposicao.codigo = GrupoExposicaoRisco.codigo_grupo_exposicao'
            ),
            array(
                'table'         => 'funcionario_setores_cargos',
                'alias'         => 'FuncionarioSetorCargo',
                'type'          => 'INNER',
                'conditions'    => 'FuncionarioSetorCargo.codigo_setor = ClienteSetor.codigo_setor '
                                    . 'AND FuncionarioSetorCargo.codigo_cargo = GrupoExposicao.codigo_cargo '
                                    . 'AND FuncionarioSetorCargo.codigo_cliente_alocacao = ClienteSetor.codigo_cliente_alocacao'
            ),
            array(
                'table'         => 'cliente_funcionario',
                'alias'         => 'ClienteFuncionario',
                'type'          => 'INNER',
                'conditions'    => 'ClienteFuncionario.codigo = FuncionarioSetorCargo.codigo_cliente_funcionario'
            ),
        );

        //filtros do where
        $conditions = array(
            'FuncionarioSetorCargo.codigo' => $codigo_funcionario_setor_cargo,
            'GrupoExposicao.codigo IN (select * from dbo.ufn_grupo_exposicao(FuncionarioSetorCargo.codigo_cliente_alocacao,FuncionarioSetorCargo.codigo_setor,FuncionarioSetorCargo.codigo_cargo,ClienteFuncionario.codigo_funcionario))'
        );

        //executa a query
        $dados_ppra = $this->find()
                            ->select($fields)
                            ->join($joins)
                            ->where($conditions)
                            ->first();

        return $dados_ppra;     
    
    }//fim verificaFuncionarioTemPpra

    /**
     * [getRiscos pega os riscos do funcionario]
     * @param  int    $codigo_funcionario [description]
     * @param  int    $codigo_unidade     [description]
     * @param  int    $codigo_setor       [description]
     * @param  int    $codigo_cargo       [description]
     * @return [type]                     [description]
     */
    public function getRiscos(int $codigo_funcionario_setor_cargo)
    {

       //campos da grupo de exposicao
        $fields = array(
            'codigo' => 'Riscos.codigo',
            'risco' => 'RHHealth.dbo.ufn_decode_utf8_string(Riscos.nome_agente)',
        );

        //relacionamentos da query
        $joins = array(
            array(
                'table'         => 'clientes_setores',
                'alias'         => 'ClienteSetor',
                'type'          => 'INNER',
                'conditions'    => 'ClienteSetor.codigo = GrupoExposicao.codigo_cliente_setor'
            ),
            array(
                'table'         => 'funcionario_setores_cargos',
                'alias'         => 'FuncionarioSetorCargo',
                'type'          => 'INNER',
                'conditions'    => 'FuncionarioSetorCargo.codigo_setor = ClienteSetor.codigo_setor '
                                    . 'AND FuncionarioSetorCargo.codigo_cargo = GrupoExposicao.codigo_cargo '
                                    . 'AND FuncionarioSetorCargo.codigo_cliente_alocacao = ClienteSetor.codigo_cliente_alocacao'
            ),
            array(
                'table'         => 'cliente_funcionario',
                'alias'         => 'ClienteFuncionario',
                'type'          => 'INNER',
                'conditions'    => 'ClienteFuncionario.codigo = FuncionarioSetorCargo.codigo_cliente_funcionario'
            ),
            array(
                'table'         => 'grupos_exposicao_risco',
                'alias'         => 'GrupoExposicaoRisco',
                'type'          => 'INNER',
                'conditions'    => 'GrupoExposicao.codigo = GrupoExposicaoRisco.codigo_grupo_exposicao'
            ),
            array(
                'table'         => 'riscos',
                'alias'         => 'Riscos',
                'type'          => 'INNER',
                'conditions'    => 'GrupoExposicaoRisco.codigo_risco = Riscos.codigo'
            ),
        );

        //filtros do where
        $conditions = array(
            'FuncionarioSetorCargo.codigo' => $codigo_funcionario_setor_cargo
        );

        //executa a query
        $dados_ppra = $this->find()
                            ->select($fields)
                            ->join($joins)
                            ->where($conditions)
                            ->all();

        return $dados_ppra;     


    }//fim getRiscos

}
