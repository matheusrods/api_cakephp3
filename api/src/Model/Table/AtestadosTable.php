<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Atestados Model
 *
 * @property \App\Model\Table\CidTable&\Cake\ORM\Association\BelongsToMany $Cid
 *
 * @method \App\Model\Entity\Atestado get($primaryKey, $options = [])
 * @method \App\Model\Entity\Atestado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Atestado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Atestado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Atestado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Atestado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Atestado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Atestado findOrCreate($search, callable $callback = null, $options = [])
 */
class AtestadosTable extends AppTable
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

        $this->setTable('atestados');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Cid', [
            'foreignKey' => 'atestado_id',
            'targetForeignKey' => 'cid_id',
            'joinTable' => 'atestados_cid'
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
            ->integer('codigo_cliente_funcionario')
            ->notEmptyString('codigo_cliente_funcionario');

        $validator
            ->integer('codigo_medico')
            ->requirePresence('codigo_medico', 'create')
            ->notEmptyString('codigo_medico');

        $validator
            ->date('data_afastamento_periodo')
            ->allowEmptyDate('data_afastamento_periodo');

        $validator
            ->date('data_retorno_periodo')
            ->allowEmptyDate('data_retorno_periodo');

        $validator
            ->scalar('afastamento_em_horas')
            ->maxLength('afastamento_em_horas', 10)
            ->allowEmptyString('afastamento_em_horas');

        $validator
            ->date('data_afastamento_hr')
            ->allowEmptyDate('data_afastamento_hr');

        $validator
            ->time('hora_afastamento')
            ->allowEmptyTime('hora_afastamento');

        $validator
            ->time('hora_retorno')
            ->allowEmptyTime('hora_retorno');

        $validator
            ->integer('codigo_motivo_esocial')
            ->allowEmptyString('codigo_motivo_esocial');

        $validator
            ->integer('codigo_motivo_licenca')
            ->requirePresence('codigo_motivo_licenca', 'create')
            ->notEmptyString('codigo_motivo_licenca');

        $validator
            ->scalar('restricao')
            ->maxLength('restricao', 100)
            ->allowEmptyString('restricao');

        $validator
            ->integer('codigo_cid_contestato')
            ->allowEmptyString('codigo_cid_contestato');

        $validator
            ->allowEmptyString('imprimi_cid_atestado');

        $validator
            ->allowEmptyString('acidente_trajeto');

        $validator
            ->scalar('endereco')
            ->maxLength('endereco', 80)
            ->allowEmptyString('endereco');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 20)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 50)
            ->allowEmptyString('complemento');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 80)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 8)
            ->allowEmptyString('cep');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_estado')
            ->allowEmptyString('codigo_estado');

        $validator
            ->integer('codigo_cidade')
            ->allowEmptyString('codigo_cidade');

        $validator
            ->integer('codigo_tipo_local_atendimento')
            ->allowEmptyString('codigo_tipo_local_atendimento');

        $validator
            ->numeric('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->integer('afastamento_em_dias')
            ->allowEmptyString('afastamento_em_dias');

        $validator
            ->boolean('habilita_afastamento_em_horas')
            ->allowEmptyString('habilita_afastamento_em_horas');

        $validator
            ->integer('codigo_func_setor_cargo')
            ->allowEmptyString('codigo_func_setor_cargo');

        $validator
            ->integer('exibir_ficha_assistencial')
            ->allowEmptyString('exibir_ficha_assistencial');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 2)
            ->allowEmptyString('estado');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->allowEmptyString('cidade');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('motivo_afastamento')
            ->maxLength('motivo_afastamento', 2)
            ->allowEmptyString('motivo_afastamento');

        $validator
            ->scalar('origem_retificacao')
            ->maxLength('origem_retificacao', 2)
            ->allowEmptyString('origem_retificacao');

        $validator
            ->scalar('tipo_acidente_transito')
            ->maxLength('tipo_acidente_transito', 2)
            ->allowEmptyString('tipo_acidente_transito');

        $validator
            ->scalar('onus_remuneracao')
            ->maxLength('onus_remuneracao', 2)
            ->allowEmptyString('onus_remuneracao');

        $validator
            ->scalar('onus_requisicao')
            ->maxLength('onus_requisicao', 2)
            ->allowEmptyString('onus_requisicao');

        $validator
            ->scalar('numero_processo')
            ->maxLength('numero_processo', 20)
            ->allowEmptyString('numero_processo');

        $validator
            ->scalar('tipo_processo')
            ->maxLength('tipo_processo', 2)
            ->allowEmptyString('tipo_processo');

        $validator
            ->scalar('codigo_documento_entidade')
            ->maxLength('codigo_documento_entidade', 14)
            ->allowEmptyString('codigo_documento_entidade');

        $validator
            ->scalar('observacao')
            ->notEmptyString('observacao');

        $validator
            ->scalar('estabelecimento')
            ->allowEmptyString('estabelecimento');

        $validator
            ->scalar('pk_externo')
            ->maxLength('pk_externo', 250)
            ->allowEmptyString('pk_externo');

        $validator
            ->integer('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_paciente')
            ->notEmptyString('codigo_paciente');

        return $validator;
    }

    /**
     * [getAtestados description]
     *
     * metodo para pegar os atestados médicos
     *
     * @param  [type] $tipo               [A-> ativo, H->historico, a variavel vai definir qual o tipo de resultado vai trazer]
     * @param  [type] $codigo_funcionario [description]
     * @return [type]                     [description]
     */
    public function getAtestados($codigo_funcionario,$tipo = 'H',$limit=10)
    {

        //monta os fields
        $fields = array(
            'codigo_atestados' => 'Atestados.codigo',
            'data_validade' => 'Atestados.data_retorno_periodo',
            'periodo' => "(CASE WHEN Atestados.afastamento_em_dias IS NOT NULL THEN CONVERT(VARCHAR(20), Atestados.afastamento_em_dias) ELSE CONVERT(VARCHAR(20), Atestados.afastamento_em_horas) END)",
            'periodo_tipo' => "(CASE WHEN Atestados.afastamento_em_dias IS NOT NULL THEN 'Dia(s)' ELSE 'Hora(s)' END)",
            'motivo' => "RHHealth.dbo.ufn_decode_utf8_string(MotivoAfastamento.descricao)",
            'nome_fantasia' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.nome_fantasia)',
            'razao_social' => 'RHHealth.dbo.ufn_decode_utf8_string(Cliente.razao_social)'
        );

        //ligacoes
        $joins = array(
            array(
                'table' => 'cliente_funcionario',
                'alias' => 'ClienteFuncionario',
                'type' => 'INNER',
                'conditions' => 'Atestados.codigo_cliente_funcionario = ClienteFuncionario.codigo'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => 'ClienteFuncionario.codigo_cliente_matricula = Cliente.codigo'
            ),
            array(
                'table' => 'motivos_afastamento',
                'alias' => 'MotivoAfastamento',
                'type' => 'LEFT',
                'conditions' => 'Atestados.codigo_motivo_licenca = MotivoAfastamento.codigo'
            )
        );

        //quantidade de registros
        // $limit = 10;

        $conditions['Atestados.ativo'] = 1;
        $conditions['ClienteFuncionario.codigo_funcionario'] = $codigo_funcionario;

        //verifica o tipo da conditions
        if($tipo == 'H') { //home
            $order = array('Atestados.data_retorno_periodo DESC');
            $conditions['Atestados.data_retorno_periodo <'] = date('Y-m-d 00:00:00');
        }
        else if($tipo == 'A') {
            $order = array('Atestados.data_retorno_periodo ASC');
            $conditions['Atestados.data_retorno_periodo >='] = date('Y-m-d 23:59:59');
        }

        //executa a query
        $dados = $this->find()
                    ->select($fields)
                    ->join($joins)
                    ->where($conditions)
                    ->limit($limit)
                    ->order($order);
                    //->all();    <--- não usar se quiser paginar na controller

        // debug($dados->sql());debug($conditions);exit;

        return $dados;

    }//fim getAtestados


    /**
     * Obter Atestados de um usuario por codigo_usuario e filtros por conditions
     *
     * @param array $conditions
     * @return array|null
     */
    public function obterAtestados(array $conditions = [], array $fields = []){



        $dados = $this->find()
                    ->select($fields)
                    ->where($conditions);

        return $dados;
    }


    /**
     * Obter atestados que vence hoje
     *
     * ex. retorno
     *[
         0 => array:117 [
            "codigo" => "72449"
            "nome" => "João Guilherme"
            "celular_usuario_sistema" => null
            "celular_usuario" => null
            "codigo_cliente_funcionario" => "45601"
            "codigo_medico" => "11860"
            "data_afastamento_periodo" => "2019-11-20"
            "data_retorno_periodo" => "2019-11-20"
            "afastamento_em_horas" => null
            "data_afastamento_hr" => null
            "hora_afastamento" => null
            "hora_retorno" => null
            "codigo_motivo_esocial" => null
            "codigo_motivo_licenca" => "4"
            "restricao" => null
            "codigo_cid_contestato" => null
            "imprimi_cid_atestado" => null
            "acidente_trajeto" => null
            "endereco" => null
            "numero" => null
            "complemento" => null
            "bairro" => null
            "cep" => null
            "codigo_usuario_inclusao" => "1"
            "data_inclusao" => "2019-10-29 16:36:08.000"
            "codigo_estado" => null
            "codigo_cidade" => null
            "codigo_tipo_local_atendimento" => null
            "latitude" => null
            "longitude" => null
            "afastamento_em_dias" => "1"
            "habilita_afastamento_em_horas" => null
            "codigo_func_setor_cargo" => null
            "exibir_ficha_assistencial" => null
            "estado" => null
            "cidade" => null
            "codigo_usuario_alteracao" => "1"
            "data_alteracao" => "2019-10-31 18:34:11.000"
            "codigo_empresa" => "1"
            "motivo_afastamento" => null
            "origem_retificacao" => null
            "tipo_acidente_transito" => null
            "onus_remuneracao" => null
            "onus_requisicao" => null
            "numero_processo" => null
            "tipo_processo" => null
            "codigo_documento_entidade" => null
            "observacao" => null
            "codigo_cliente" => "54935"
            "codigo_funcionario" => null
            "codigo_setor" => null
            "codigo_cargo" => null
            "admissao" => "2010-11-05"
            "ativo" => "1"
            "matricula" => ""
            "data_demissao" => null
            "centro_custo" => ""
            "data_ultima_aso" => null
            "aptidao" => null
            "turno" => null
            "codigo_cliente_matricula" => "54935"
            "matricula_candidato" => null
            "data_nascimento" => "1989-11-19"
            "rg" => "2.523.970"
            "rg_orgao" => " "
            "cpf" => "02025002173"
            "sexo" => "M"
            "status" => null
            "ctps" => " "
            "ctps_data_emissao" => null
            "gfip" => " "
            "rg_data_emissao" => ""
            "nit" => " "
            "ctps_serie" => " "
            "cns" => ""
            "ctps_uf" => ""
            "email" => "joao@codzen.com.br"
            "estado_civil" => null
            "deficiencia" => null
            "rg_uf" => "2."
            "nome_mae" => null
            "apelido_sistema" => null
            "telefone" => "51991562777"
            "celular" => null
            "avatar" => "https://api.rhhealth.com.br/nina/2019/10/29/F39E2DA1-52F5-0A7D-8FAD-F222359027B4.jpeg"
            "codigo_usuario" => "72449"
            "ultimo_acesso" => null
            "notificacao" => "1"
            "codigo_sistema" => "1"
            "senha" => "p6A3XBpRs071ef3s0SqIYK6GHv2Grk2hTxLwM/pvUqb+w7Yo9IOZrJuHW4AhyOKyuHmvnp6mREwE6tBOc3+g/Vrsau5dwY5VU8ebabfl+X4wItdDttoYF8jH8eQaukRJ9WtItodSjTLNaiOf7J/rsrQdqjcjVwen+hMg9PpTAOo="
            "token_push" => "cYtOyR9MJMo:APA91bGuLerz-MvjEMXDG-1-q-FZ7SrBIZVmfezC_jg8T-G52IJLJi9Mc7Qp3ERU9TgBygkKss7UYXuDNGvauLw1_a898DoDafqhI0hCWN-cr4RzUxY5Gnsgt_7IpCeWvGdO5ceLnpHN"
            "token" => null
            "platform" => null
            "cod_verificacao" => null
            "token_chamadas" => null
            "model" => null
            "foreign_key" => null
            "apelido" => "02025002173"
            "codigo_uperfil" => "9"
            "alerta_portal" => "0"
            "alerta_email" => "0"
            "alerta_sms" => "0"
            "fuso_horario" => null
            "horario_verao" => null
            "cracha" => null
            "data_senha_expiracao" => null
            "admin" => "0"
            "codigo_usuario_pai" => null
            "restringe_base_cnpj" => "0"
            "codigo_departamento" => "1"
            "codigo_filial" => null
            "codigo_proposta_credenciamento" => null
            "codigo_fornecedor" => null
            "usuario_dados_id" => null
            "usuario_multi_empresa" => null
            "codigo_corretora" => null
            "alerta_sm_usuario" => null
        ]
     *
     * @param array $conditions
     * @return void
     */
    public function obterAtestadosPorVencimento(array $conditions = [])
    {
        if(empty($conditions)){
            $conditions = ['data_retorno_periodo'=>date('Y-m-d')];
        }

        $strSql = '
        select
            u.codigo as codigo_usuario,
            -- u.nome as nome,
            f.nome as nome,
            ud.telefone as telefone,
            us.token_push as token_push,
            us.platform as platform,
            ate.codigo as codigo_atestado
            --,*
        from atestados ate
            inner join cliente_funcionario cf on ate.codigo_cliente_funcionario = cf.codigo
            inner join funcionarios f on cf.codigo_funcionario = f.codigo
            inner join usuarios_dados ud on f.cpf = ud.cpf
            inner join usuario_sistema us on ud.codigo_usuario = us.codigo_usuario
            inner join usuario u on ud.codigo_usuario = u.codigo
            left join push_outbox po on (po.foreign_key = ate.codigo and po.model = \'App\Command\NotificaAtestadosCommand\')
        where ate.data_retorno_periodo = :data_retorno_periodo
        and ate.afastamento_em_horas IS NULL
        and ud.notificacao = 1
            and ate.ativo = 1
            and po.codigo is null
            and us.token_push IS NOT NULL
            and us.platform IS NOT NULL
        ' ;

        if(isset($conditions['codigo_usuario'])){
            $strSql = $strSql . ' AND u.codigo = :codigo_usuario';
        }

        if(isset($conditions['codigo_cliente'])){
            $strSql = $strSql . ' AND cf.codigo_cliente = :codigo_cliente';
        }

        try {
            $connection = ConnectionManager::get('default');
            return  $connection->execute($strSql, $conditions )->fetchAll('assoc');
        } catch (\Exception $e) {
             return ['error'=>$e->getMessage()];
        }

        // //campos do select
        // $fields = [
        //     'codigo_usuario' => 'Usuario.codigo',
        //     'nome' => 'Usuario.nome',
        //     'email' => 'Usuario.email',
        //     'data_nascimento' => 'UsuariosDados.data_nascimento',
        //     'celular' => 'UsuariosDados.celular',
        //     'telefone' => 'UsuariosDados.telefone',
        //     'cpf' => 'UsuariosDados.cpf',
        //     'sexo' => 'UsuariosDados.sexo',
        //     'senha' => 'Usuario.senha',
        //     'notificacao' => "(CASE WHEN UsuarioSistema.codigo IS NOT NULL THEN 1 ELSE 0 END)",
        //     'tokenpush' => "UsuarioSistema.token_push",
        //     'platform' => "UsuarioSistema.platform",
        //     'cliente.codigo',
        //     'cliente.nome_fantasia',
        //     'cliente.razao_social',
        //     'contato_emergencia.nome',
        //     'contato_emergencia.telefone',
        //     'contato_emergencia.celular',
        //     'contato_emergencia.grau_parentesco',
        //     'contato_emergencia.email',
        // ];

        // //monta os joins
        // $joins = [
        //     [
        //         'table' => 'usuarios_dados',
        //         'alias' => 'UsuariosDados',
        //         'type' => 'INNER',
        //         'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario'
        //     ],
        //     [
        //         'table' => 'usuario_multi_cliente',
        //         'alias' => 'UsuarioMultiCliente',
        //         'type' => 'LEFT',
        //         'conditions' => 'UsuariosDados.codigo_usuario = UsuarioMultiCliente.codigo_usuario'
        //     ],
        //     [
        //         'table' => 'usuario_sistema',
        //         'alias' => 'UsuarioSistema',
        //         'type' => 'LEFT',
        //         'conditions' => 'Usuario.codigo = UsuarioSistema.codigo_usuario'
        //     ],
        //     [
        //         'table' => 'cliente',
        //         'alias' => 'cliente',
        //         'type' => 'LEFT',
        //         'conditions' => 'Usuario.codigo_cliente = cliente.codigo OR cliente.codigo = UsuarioMultiCliente.codigo_cliente'
        //     ],
        //     [
        //         'table' => 'usuario_contato_emergencia',
        //         'alias' => 'contato_emergencia',
        //         'type' => 'LEFT',
        //         'conditions' => 'Usuario.codigo = contato_emergencia.codigo_usuario AND contato_emergencia.ativo = 1'
        //     ],
            // [
            //     'table' => 'cliente_funcionario',
            //     'alias' => 'ClienteFuncionario',
            //     'type' => 'LEFT',
            //     'conditions' => 'ClienteFuncionario.codigo_cliente = Cliente.codigo'
            // ],
            // [
            //     'table' => 'atestados',
            //     'alias' => 'Atestados',
            //     'type' => 'LEFT',
            //     'conditions' => 'Atestados.codigo_cliente_funcionario = ClienteFuncionario.codigo'
            // ],

            //     inner join cliente_funcionario cf on ate.codigo_cliente_funcionario = cf.codigo
            //     inner join funcionarios f on cf.codigo_funcionario = f.codigo
            //     inner join usuarios_dados ud on f.cpf = ud.cpf
            //     inner join usuario_sistema us on ud.codigo_usuario = us.codigo_usuario
            //     inner join usuario u on ud.codigo_usuario = u.codigo
            // where ate.data_retorno_periodo = '2019-11-20'
    //     ];

    //     try {

    //         //executa os dados
    //         $dados = $this->find()
    //             ->select([])
    //             ->join($joins)
    //             ->where([])
    //             ->all();

    //     } catch (\Exception $e) {
    //         return ['error'=>$e->getMessage()];
    //         //return ['error'=>'Erro na consulta a base de dados'];
    //     }

    //     return $dados;
    // }

    }

    public function getAtestadosPaciente($codigo_cliente_funcionario, $codigo_medico)
    {
        $fields = array(
            'codigo' => 'Atestados.codigo',
            'codigo_cliente_funcionario' => 'Atestados.codigo_cliente_funcionario',
            'codigo_paciente' => 'Atestados.codigo_paciente',
            'codigo_medico' => 'Atestados.codigo_medico',
            'data_afastamento_periodo' => 'Atestados.data_afastamento_periodo',
            'data_retorno_periodo' => 'Atestados.data_retorno_periodo',
            'afastamento_em_horas' => 'Atestados.afastamento_em_horas',
            'hora_afastamento' => 'Atestados.hora_afastamento',
            'hora_retorno' => 'Atestados.hora_retorno',
            'afastamento_em_dias' => 'Atestados.afastamento_em_dias',
            'restricao' => 'Atestados.restricao',
            'ativo' => 'Atestados.ativo',
        );

        $conditions = " Atestados.codigo_cliente_funcionario = ". $codigo_cliente_funcionario ." and Atestados.codigo_medico = ". $codigo_medico ." ";

        $result = $this->find()
            ->select($fields)
            ->where($conditions)
            ->limit(20);

        return $result;
    }

}
