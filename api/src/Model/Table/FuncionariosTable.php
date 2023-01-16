<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\Comum;
use App\Utils\EncodingUtil;
use Cake\Datasource\ConnectionManager;

/**
 * Funcionarios Model
 *
 * @property \App\Model\Table\MedicamentosTable&\Cake\ORM\Association\BelongsToMany $Medicamentos
 * @property \App\Model\Table\MedicosTable&\Cake\ORM\Association\BelongsToMany $Medicos
 *
 * @method \App\Model\Entity\Funcionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Funcionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Funcionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Funcionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Funcionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario findOrCreate($search, callable $callback = null, $options = [])
 */
class FuncionariosTable extends AppTable
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

        $this->setTable('funcionarios');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Medicamentos', [
            'foreignKey' => 'funcionario_id',
            'targetForeignKey' => 'medicamento_id',
            'joinTable' => 'funcionarios_medicamentos'
        ]);
        $this->belongsToMany('Medicos', [
            'foreignKey' => 'funcionario_id',
            'targetForeignKey' => 'medico_id',
            'joinTable' => 'funcionarios_medicos'
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
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->date('data_nascimento')
            ->requirePresence('data_nascimento', 'create')
            ->notEmptyDate('data_nascimento');

        $validator
            ->scalar('rg')
            ->maxLength('rg', 20)
            ->requirePresence('rg', 'create')
            ->notEmptyString('rg');

        $validator
            ->scalar('rg_orgao')
            ->maxLength('rg_orgao', 7)
            ->requirePresence('rg_orgao', 'create')
            ->notEmptyString('rg_orgao');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 25)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 2)
            ->requirePresence('sexo', 'create')
            ->notEmptyString('sexo');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->scalar('ctps')
            ->maxLength('ctps', 25)
            ->allowEmptyString('ctps');

        $validator
            ->dateTime('ctps_data_emissao')
            ->allowEmptyDateTime('ctps_data_emissao');

        $validator
            ->scalar('gfip')
            ->maxLength('gfip', 25)
            ->allowEmptyString('gfip');

        $validator
            ->scalar('rg_data_emissao')
            ->maxLength('rg_data_emissao', 25)
            ->allowEmptyString('rg_data_emissao');

        $validator
            ->scalar('nit')
            ->maxLength('nit', 25)
            ->allowEmptyString('nit');

        $validator
            ->scalar('ctps_serie')
            ->maxLength('ctps_serie', 25)
            ->allowEmptyString('ctps_serie');

        $validator
            ->scalar('cns')
            ->maxLength('cns', 25)
            ->allowEmptyString('cns');

        $validator
            ->scalar('ctps_uf')
            ->maxLength('ctps_uf', 25)
            ->allowEmptyString('ctps_uf');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->integer('estado_civil')
            ->allowEmptyString('estado_civil');

        $validator
            ->integer('deficiencia')
            ->allowEmptyString('deficiencia');

        $validator
            ->scalar('rg_uf')
            ->maxLength('rg_uf', 25)
            ->allowEmptyString('rg_uf');

        $validator
            ->scalar('nome_mae')
            ->maxLength('nome_mae', 80)
            ->allowEmptyString('nome_mae');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

    /**
     * [getFuncionarioUsuarioCliente description]
     *
     * metodo para validar o codigo do funcionario usuario cliente
     *
     * @param  [type] $cpf                   [description]
     * @param  [type] $codigo_cliente        [description]
     * @param  [type] $codigo_nina_validacao [description]
     * @return [type]                        [description]
     */
    public function getFuncionarioUsuarioCliente($cpf, $codigo_cliente = null, $codigo_nina_validacao = null)
    {

        //pega as empresas que estão relacioanadas ao cpf e com codigo de uperfil 9 nina
        $dados = $this->find()
                    ->select([
                        'Cliente.codigo',
                        'ClienteFuncionario.codigo',
                        'Cliente.nome_fantasia',
                        'codigo_usuario'=>'ISNULL(Usuario.codigo,UsuarioMultiCliente.codigo_usuario)'
                        ])
                    ->join([
                        'ClienteFuncionario' => [
                            'table' => 'cliente_funcionario',
                            'type' => 'INNER',
                            'conditions' => "ClienteFuncionario.codigo_funcionario = Funcionarios.codigo AND ClienteFuncionario.ativo <> '0' AND ClienteFuncionario.data_demissao IS NULL",
                        ],
                        'ClienteMatriz' => [
                            'table' => 'cliente',
                            'type' => 'INNER',
                            'conditions' => 'ClienteMatriz.codigo = ClienteFuncionario.codigo_cliente_matricula',
                        ],
                        'UsuariosDados' => [
                            'table' => 'usuarios_dados',
                            'type' => 'LEFT',
                            'conditions' => 'Funcionarios.cpf = UsuariosDados.cpf',
                        ],
                        'FuncionarioSetorCargo' => [
                            'table' => 'funcionario_setores_cargos',
                            'type' => 'INNER',
                            'conditions' => 'FuncionarioSetorCargo.codigo = (SELECT TOP 1 _fsc.codigo
                                                                            FROM funcionario_setores_cargos _fsc
                                                                                 INNER JOIN cliente cli on _fsc.codigo_cliente_alocacao=cli.codigo and cli.e_tomador <> 1
                                                                            WHERE _fsc.codigo_cliente_funcionario = ClienteFuncionario.codigo
                                                                                AND _fsc.data_fim IS NULL
                                                                            ORDER BY _fsc.codigo desc)'
                        ],
                        'Cliente' => [
                            'table' => 'cliente',
                            'type' => 'LEFT',
                            'conditions' => 'Cliente.codigo = FuncionarioSetorCargo.codigo_cliente_alocacao'
                        ],

                        'Usuario' => [
                            'table' => 'usuario',
                            'type' => 'LEFT',
                            'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo AND Usuario.codigo_uperfil = 9 AND Usuario.codigo_cliente = Cliente.codigo',
                        ],
                        'UsuarioMultiCliente' => [
                            'table' => 'usuario_multi_cliente',
                            'type' => 'LEFT',
                            'conditions' => 'UsuarioMultiCliente.codigo_usuario = UsuariosDados.codigo_usuario AND Cliente.codigo = UsuarioMultiCliente.codigo_cliente',
                        ],
                    ])
                    ->where(['Funcionarios.cpf' => Comum::soNumero($cpf)]);

        //inclui o codigo_cliente na conditions
        if(!is_null($codigo_cliente)) {
            $dados->where(['OR' => ['ClienteMatriz.codigo' => $codigo_cliente,'Cliente.codigo' => $codigo_cliente, ]]);
        }

        //verifica se existe o codigo do nina para validar
        if(!is_null($codigo_nina_validacao)) {
            $dados->where(['OR' => ['ClienteMatriz.codigo_nina_validacao' => $codigo_nina_validacao,'Cliente.codigo_nina_validacao' => $codigo_nina_validacao]]);
        }

        // debug(array($cpf, $codigo_cliente, $codigo_nina_validacao));
        // debug($dados->sql());
        // exit;

        $result = $dados->all()->toArray();

        return $result;

    }//fim getFuncionarioUsuarioCliente

    public function getCodigoFuncionario($cpf){
        $where = ['cpf'=>$cpf];
        return $this->find()->where($where)->toArray();
    }


    public function getValidaCodigoNinaCliente($cpf, $codigo_cliente = null, $codigo_nina_validacao = null)
    {
        $iconv = new EncodingUtil();

        $result = $this->getFuncionarioUsuarioCliente($cpf, $codigo_cliente, $codigo_nina_validacao);

        //verifica se existe dados
        if (!empty($result)) {
            $clientes = array();

            //varre os dados
            foreach ($result as $dado) {
                $vinculado = 0;
                if (!empty($dado['codigo_usuario'])) {
                    $vinculado = 1;
                }

                $clientes[] = array(
                    'codigo_cliente' => $dado['Cliente']['codigo'],
                    'nome_cliente' => $iconv->convert($dado['Cliente']['nome_fantasia']),
                    'flag_vinculado' => $vinculado,
                    'codigo_usuario' => $dado['codigo_usuario'],
                );
            }//fim foreach

            return $clientes;
        }//fim verificacao
        else {
            return false;
        }
    }//fim getValidaCodigoNinaCliente

    /**
     * Obter array com codigo_cliente vinculado ao CPF
     *
     * @param string $cpf
     * @return array
     */
    public function obterCodigoClienteVinculado($cpf = null)
    {
        $codigo_cliente = [];
        $vinculos = $this->getValidaCodigoNinaCliente($cpf);

        if($vinculos){

            foreach ($vinculos as $key => $value) {

                if($value['flag_vinculado'] == 0){
                    continue;
                } else {
                    $codigo_cliente[] = $value['codigo_cliente'];
                }
            }

            return $codigo_cliente;
        }

        return null;
    }

    public function getPacienteDetalhe(int $codigo = null)
    {
        //Condições para retornar os dados dos funcionários
        $fields = array(
            'codigo'  => 'Funcionarios.codigo',
            'cpf'     => 'Funcionarios.cpf',
            'nome'    => 'Funcionarios.nome',
            'sexo'    => 'Funcionarios.sexo',
            'rg'    => 'Funcionarios.rg',
            'email'    => 'Funcionarios.email',
            'foto'    => 'ISNULL(Funcionarios.foto,UsuariosDados.avatar)',
            'matricula'    => 'ClienteFuncionario.matricula',
            'data_nascimento' => 'Funcionarios.data_nascimento',
            'empresa' => 'GruposEconomicos.descricao',
            'setor'   => 'Setores.descricao',
            'cargo'   => 'cargos.descricao',
            'tipo'    => "'funcionario'",
            'codigo_funcionario_setor_cargo' => 'FuncionariosSetoresCargos.codigo'
        );

        $joins  = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type'  => 'INNER',
                'conditions' => 'Funcionarios.codigo = ClienteFuncionario.codigo_funcionario',
            ),
            array(
                'table' => 'grupos_economicos',
                'alias' => 'GruposEconomicos',
                'type'  => 'INNER',
                'conditions' => 'GruposEconomicos.codigo_cliente = ClienteFuncionario.codigo_cliente',
            ),
            array(
                'table' => 'funcionario_setores_cargos',
                'alias' => 'FuncionariosSetoresCargos',
                'type'  => 'INNER',
                'conditions' => 'FuncionariosSetoresCargos.codigo_cliente_funcionario = ClienteFuncionario.codigo AND FuncionariosSetoresCargos.data_fim IS NULL',
            ),
            array(
                'table' => 'cargos',
                'alias' => 'Cargos',
                'type'  => 'INNER',
                'conditions' => 'Cargos.codigo = FuncionariosSetoresCargos.codigo_cargo',
            ),
            array(
                'table' => 'setores',
                'alias' => 'Setores',
                'type'  => 'INNER',
                'conditions' => 'Setores.codigo = FuncionariosSetoresCargos.codigo_setor'
            ),
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type'  => 'LEFT',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            )
        );

        $conditions = "Funcionarios.codigo = ".$codigo." AND Funcionarios.codigo IN (select codigo_funcionario from pedidos_exames where codigo_funcionario = ".$codigo.")";

        //Condições para retornar os dados dos pacientes
        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->limit(20);

        $pacientes = TableRegistry::getTableLocator()->get('Pacientes');

        $pacienteFields = array(
            'codigo'    => 'Pacientes.codigo',
            'cpf'       => 'Pacientes.cpf',
            'nome'      => 'Pacientes.nome',
            'sexo'      => 'Pacientes.sexo',
            'rg'        => 'Pacientes.rg',
            'email'     => 'Pacientes.email',
            'foto'      => 'NULL',
            'matricula' => 'NULL',
            'data_nascimento' => 'Pacientes.data_nascimento',
            'empresa'   => 'PacientesDadosTrabalho.empresa',
            'setor'     => 'PacientesDadosTrabalho.setor',
            'cargo'     => 'NULL',
            'tipo'      => "'paciente'",
            'codigo_funcionario_setor_cargo' => 'NULL',
        );

        $pacienteJoins = array(
            array(
                'table' => 'pacientes_dados_trabalho',
                'alias' => 'PacientesDadosTrabalho',
                'type'  => 'INNER',
                'conditions' => 'Pacientes.codigo = PacientesDadosTrabalho.codigo_paciente'
            )
        );

        $pacienteConditions = " Pacientes.codigo = " . $codigo . " ";

        $queryPacientes = $pacientes->find()
            ->select($pacienteFields)
            ->join($pacienteJoins)
            ->where($pacienteConditions)
            ->limit(20);
        //Verifica se tanto o funcionario quanto o paciente existe e retorna os dados
        return $queryPacientes->union($dados);
    }

    public function getColaboradorDetalhe(int $codigo = null)
    {
        $dados = array();

        $query = "select

                RHHealth.dbo.ufn_decode_utf8_string(f.nome) AS nome,
                f.cpf,
                cf.matricula,
                f.sexo,
                f.data_nascimento,
                f.foto,
                fsc.data_inicio,
                fsc.data_fim,

                RHHealth.dbo.ufn_decode_utf8_string(ge.descricao) as empresa,
                RHHealth.dbo.ufn_decode_utf8_string(s.descricao) as setor,
                RHHealth.dbo.ufn_decode_utf8_string(c.descricao) as cargo,
                fsc.codigo_cliente_alocacao,

                CAST(
                (select RHHealth.dbo.ufn_decode_utf8_string(r.nome_agente) as riscos
                from clientes_setores cs
                left join grupo_exposicao ge on ge.codigo_cliente_setor = cs.codigo
                left join grupos_exposicao_risco ger on ge.codigo = ger.codigo_grupo_exposicao
                left join riscos r on ger.codigo_risco = r.codigo
                where fsc.codigo_setor = cs.codigo_setor AND fsc.codigo_cliente_alocacao = cs.codigo_cliente
                group by r.nome_agente
                FOR xml PATH ('')) AS text) riscos,

                CAST((select fcq.label as label
                from pedidos_exames pe
                inner join fichas_clinicas fc on fc.codigo = (select top 1 codigo from fichas_clinicas where codigo_pedido_exame = pe.codigo order by codigo desc)
                inner join fichas_clinicas_respostas fcr ON fcr.codigo_ficha_clinica = fc.codigo
                inner join fichas_clinicas_questoes fcq on fcr.codigo_ficha_clinica_questao = fcq.codigo
                where pe.codigo_func_setor_cargo = fsc.codigo
                and fcr.resposta <> '0'
                group by fcq.label
                FOR xml PATH ('')) AS text) dados_medicos,

                CAST((select fcr.campo_livre AS farmaco
                from pedidos_exames pe
                inner join fichas_clinicas fc on fc.codigo = (select top 1 codigo from fichas_clinicas where codigo_pedido_exame = pe.codigo order by codigo desc)
                inner join fichas_clinicas_respostas fcr ON fcr.codigo_ficha_clinica = fc.codigo
                where pe.codigo_func_setor_cargo = fsc.codigo
                and fcr.campo_livre IS NOT NULL
                and fcr.campo_livre like '%farmaco%'
                group by fcr.campo_livre
                FOR xml PATH ('')) AS text) dados_medicacoes

            from cliente_funcionario cf
            inner join funcionarios f on cf.codigo_funcionario = f.codigo
            inner join funcionario_setores_cargos fsc on cf.codigo = fsc.codigo_cliente_funcionario
            inner join setores s on fsc.codigo_setor = s.codigo
            inner join cargos c on fsc.codigo_cargo = c.codigo
            inner join cliente cl on cl.codigo = fsc.codigo_cliente_alocacao
            inner join grupos_economicos ge on ge.codigo_cliente = cf.codigo_cliente

            where f.codigo = ".$codigo."

            and fsc.data_fim IS NULL
        ";

        // debug($query);exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return $dados;
    }

    public function getFuncionariosEmpresasDetalhe(int $codigo = null)
    {
        $dados = array();

        $query = "select
                RHHealth.dbo.ufn_decode_utf8_string(cl.nome_fantasia) as empresa,
                RHHealth.dbo.ufn_decode_utf8_string(s.descricao) as setor,
                RHHealth.dbo.ufn_decode_utf8_string(c.descricao) as cargo,
                fsc.codigo_cliente_alocacao,
                RHHealth.dbo.ufn_decode_utf8_string(ce.cidade) as cidade,
                ce.estado_abreviacao,
                CAST(
                    (select RHHealth.dbo.ufn_decode_utf8_string(r.nome_agente) as riscos
                    from clientes_setores cs
                        left join grupo_exposicao ge on ge.codigo_cliente_setor = cs.codigo
                        left join grupos_exposicao_risco ger on ge.codigo = ger.codigo_grupo_exposicao
                        left join riscos r on ger.codigo_risco = r.codigo
                    where fsc.codigo_setor = cs.codigo_setor AND fsc.codigo_cliente_alocacao = cs.codigo_cliente
                    FOR xml PATH ('')) AS text) riscos
            from cliente_funcionario cf
            inner join funcionarios f on cf.codigo_funcionario = f.codigo
            inner join funcionario_setores_cargos fsc on cf.codigo = fsc.codigo_cliente_funcionario
            inner join setores s on fsc.codigo_setor = s.codigo
            inner join cargos c on fsc.codigo_cargo = c.codigo
            inner join cliente cl on cl.codigo = fsc.codigo_cliente_alocacao
            left join cliente_endereco ce on ce.codigo_cliente = cl.codigo
            inner join grupos_economicos ge on ge.codigo_cliente = cf.codigo_cliente
            where fsc.data_fim IS NULL
                and cl.e_tomador <> 1
                and cf.codigo = ".$codigo."
        ";

        // debug($query);exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return $dados;
    }
    /*
    * Busca os postos de trabalhos ativos do colaborador
    */
    function getPostosAtivos(int $codigo = null){
        $query = "
            select
                RHHealth.dbo.ufn_decode_utf8_string(c.razao_social) as razao_social,
                RHHealth.dbo.ufn_decode_utf8_string(c.nome_fantasia) as nome_fantasia,
                fsc.codigo,
                fsc.data_inicio,
                fsc.data_fim,
                fsc.codigo_cliente,
                fsc.codigo_setor,
                fsc.codigo_cargo,
                fsc.codigo_cliente_alocacao
            from cliente_funcionario cf
                inner join funcionario_setores_cargos fsc on cf.codigo = fsc.codigo_cliente_funcionario
                inner join cliente c on fsc.codigo_cliente_alocacao = c.codigo
            where cf.codigo_funcionario = ".$codigo."
                and c.e_tomador =1
                and fsc.data_fim is null
        ";

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return $dados;
    }

    /**
     * [getFuncionarioEndereco metodo para pegar o endereco da casa do funcionario pelo codigo de usuario do lyn
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]         [description]
     */
    public function getFuncionarioEndereco($codigo_usuario)
    {

        //monta a query para pegar o endereco do funcionario
        $query = "
        SELECT TOP 1
            fe.numero as numero,
            RHHealth.dbo.ufn_decode_utf8_string(fe.logradouro) as logradouro,
            fe.cep as cep,
            RHHealth.dbo.ufn_decode_utf8_string(fe.complemento) as complemento,
            RHHealth.dbo.ufn_decode_utf8_string(fe.bairro) as bairro,
            RHHealth.dbo.ufn_decode_utf8_string(fe.cidade) as cidade,
            RHHealth.dbo.ufn_decode_utf8_string(fe.estado_descricao) as estado_descricao,
            fe.estado_abreviacao as estado_abreviacao,
            fe.codigo_empresa
        FROM RHHealth.dbo.usuarios_dados ud
            INNER JOIN  RHHealth.dbo.funcionarios f on f.cpf = ud.cpf
            INNER JOIN  RHHealth.dbo.funcionarios_enderecos fe on f.codigo = fe.codigo_funcionario
        WHERE ud.codigo_usuario = {$codigo_usuario}
        GROUP BY fe.numero,
            fe.logradouro,
            fe.cep,
            fe.complemento,
            fe.bairro,
            fe.cidade,
            fe.estado_descricao,
            fe.estado_abreviacao,
            fe.codigo_empresa;
        ";

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return !empty($dados) ? $dados[0] : [];

    }// fim getFuncionarioEndereco($codigo)

}
