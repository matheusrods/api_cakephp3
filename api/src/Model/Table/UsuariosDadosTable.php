<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuariosDados Model
 *
 * @method \App\Model\Entity\UsuariosDado get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosDado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosDado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosDado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosDado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosDado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosDado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosDado findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosDadosTable extends AppTable
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

        $this->setTable('usuarios_dados');
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
            ->scalar('cpf')
            ->maxLength('cpf', 11)
            //->requirePresence('cpf', 'create')
            ->allowEmptyString('cpf');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->allowEmptyString('sexo');

        $validator
            ->date('data_nascimento')
            ->allowEmptyDate('data_nascimento');

        $validator
            ->scalar('apelido_sistema')
            ->maxLength('apelido_sistema', 100)
            ->allowEmptyString('apelido_sistema');

        $validator
            ->scalar('telefone')
            ->maxLength('telefone', 20)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->maxLength('celular', 20)
            ->allowEmptyString('celular');

        $validator
            ->scalar('avatar')
            ->maxLength('avatar', 255)
            ->allowEmptyString('avatar');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 8)
            ->allowEmptyString('cep');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 2)
            ->allowEmptyString('estado');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('endereco')
            ->maxLength('endereco', 255)
            ->allowEmptyString('endereco');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 25)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 100)
            ->allowEmptyString('complemento');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('ultimo_acesso')
            ->allowEmptyDateTime('ultimo_acesso');

        $validator
            ->integer('notificacao')
            ->allowEmptyString('notificacao');

        return $validator;
    }

    /**
     * [getgetUsuariosPush description]
     *
     * recupera do banco de dados todos os usuarios que aceitaram receber notificacao push
     *
     * @return [type] [description]
     */
    public function getUsuariosPush()
    {

        //monta o select
        $fields = array(
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_nome' => 'Usuario.nome',
            'login' => 'Usuario.apelido',
            'cpf' => 'Funcionario.cpf',
            'nome_funcionario'=>'Funcionario.nome',
            'data_nascimento'=> 'Funcionario.data_nascimento',
            'celular' => "(CASE
                                WHEN UsuariosDados.celular <> '' THEN UsuariosDados.celular
                                WHEN UsuarioSistema.celular <> '' THEN UsuarioSistema.celular
                                WHEN UsuariosDados.telefone <> '' THEN UsuariosDados.telefone
                            END)",
            'token_push'=>'UsuarioSistema.token_push',
            'platform'=>'UsuarioSistema.platform'
        );

        //monta os joins
        $joins = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionario.cpf',
            ),
            array(
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'INNER',
                'conditions' => 'UsuarioSistema.codigo = (SELECT TOP 1 codigo FROM usuario_sistema WHERE codigo_usuario = Usuariosdados.codigo_usuario ORDER BY codigo DESC)',
            ),
        );

        //monta os filtros
        $conditions = array(
            'Usuario.ativo' => 1,
            'UsuariosDados.notificacao' => 1,
            'UsuarioSistema.platform IS NOT NULL'
        );

        $dados = $this->find()
                        ->select($fields)
                        ->join($joins)
                        ->where($conditions)
                        ->enableHydration(false)
                        ->toArray();

        return $dados;

    }//fim getUsuariosPush

    public function notificacaoUsuariosAvalieAClinica() {
        //monta o select
        $select = array(
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_nome' => 'Usuario.nome',
            'login' => 'Usuario.apelido',
            'cpf' => 'Funcionario.cpf',
            'nome_funcionario'=>'Funcionario.nome',
            'data_nascimento'=> 'Funcionario.data_nascimento',
            'celular' => "(CASE
                                WHEN UsuariosDados.celular <> '' THEN UsuariosDados.celular
                                WHEN UsuarioSistema.celular <> '' THEN UsuarioSistema.celular
                                WHEN UsuariosDados.telefone <> '' THEN UsuariosDados.telefone
                            END)",
            'token_push' => 'UsuarioSistema.token_push',
            'platform' => 'UsuarioSistema.platform',
            'codigo_pedido_exame' => 'ItensPedidosExames.codigo',
            'nome_credenciado' => 'Fornecedores.nome'
        );

        //monta os joins
        $joins = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo and Usuario.ativo = 1',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionario.cpf',
            ),
            array(
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'INNER',
                'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosDados.codigo_usuario',
            ),
            array(
                'table' => 'pedidos_exames',
                'alias' => 'PedidosExames',
                'type' => 'INNER',
                'conditions' => 'Funcionario.codigo = PedidosExames.codigo_funcionario AND PedidosExames.codigo_status_pedidos_exames <> 5',
            ),
            array(
                'table' => 'itens_pedidos_exames',
                'alias' => 'ItensPedidosExames',
                'type' => 'INNER',
                'conditions' => 'PedidosExames.codigo = ItensPedidosExames.codigo_pedidos_exames',
            ),
            array(
                'table' => 'itens_pedidos_exames_baixa',
                'alias' => 'ItensPedidosExamesBaixa',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo = ItensPedidosExamesBaixa.codigo_itens_pedidos_exames',
            ),
            array(
                'table' => 'fornecedores',
                'alias' => 'Fornecedores',
                'type' => 'INNER',
                'conditions' => 'ItensPedidosExames.codigo_fornecedor = Fornecedores.codigo',
            ),
//            array(
//                'table' => 'fornecedores_avaliacoes',
//                'alias' => 'FornecedoresAvaliacoes',
//                'type' => 'LEFT',
//                'conditions' => 'FornecedoresAvaliacoes.codigo_fornecedor = Fornecedores.codigo',
//            ),
            array(
                'table' => 'push_outbox',
                'alias' => 'PushOutbox',
                'type' => 'LEFT',
                'conditions' => 'PushOutbox.foreign_key = ItensPedidosExames.codigo',
            ),
        );

        //monta os filtros
        $conditions = array(
            'UsuariosDados.notificacao' => 1,
            'PushOutbox.codigo is null',
            'UsuarioSistema.platform IS NOT NULL',
            'ItensPedidosExamesBaixa.data_realizacao_exame ' => date('Y-m-d')
        );

        //GroupBy
        $groupBy = array(
            'UsuarioSistema.token_push',
            'Usuario.codigo',
            'Usuario.nome',
            'Usuario.apelido',
            'UsuarioSistema.platform',
            'Funcionario.cpf',
            'Funcionario.nome',
            'Funcionario.data_nascimento',
            'ItensPedidosExames.codigo',
            'UsuariosDados.celular',
            'UsuarioSistema.celular',
            'UsuariosDados.telefone',
            'Fornecedores.nome'
        );

        $dados = $this->find()
            ->select($select)
            ->join($joins)
            ->where($conditions)
            ->group($groupBy)
            ->enableHydration(false)
            ->toArray()
        ;

        return $dados;

    }

    public function notificaUsuariosMedicamento() {
        //monta o select
        $select = array(
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_nome' => 'Usuario.nome',
            'login' => 'Usuario.apelido',
            'cpf' => 'Funcionario.cpf',
            'nome_funcionario'=>'Funcionario.nome',
            'data_nascimento'=> 'Funcionario.data_nascimento',
            'celular' => "(CASE
                                WHEN UsuariosDados.celular <> '' THEN UsuariosDados.celular
                                WHEN UsuarioSistema.celular <> '' THEN UsuarioSistema.celular
                                WHEN UsuariosDados.telefone <> '' THEN UsuariosDados.telefone
                            END)",
            'token_push' => 'UsuarioSistema.token_push',
            'platform' => 'UsuarioSistema.platform',
            'medicamento' => 'CONCAT(Medicamentos.descricao, \' \', Medicamentos.posologia)',
            'usuario_medicamento_status_codigo' => 'UsuariosMedicamentosStatus.codigo',
            'frequencia_dias' => 'UsuariosMedicamentos.frequencia_dias',
            'frequencia_horarios' => 'UsuariosMedicamentos.frequencia_horarios',
            'uso_continuo' => 'UsuariosMedicamentos.uso_continuo',
            'dias_da_semana' => 'UsuariosMedicamentos.dias_da_semana',
            'frequencia_uso' => 'UsuariosMedicamentos.frequencia_uso',
            'horario_inicio_uso' => 'UsuariosMedicamentos.horario_inicio_uso',
            'frequencia_dias_intercalados' => 'UsuariosMedicamentos.frequencia_dias_intercalados',
            'periodo_tratamento_inicio' => 'UsuariosMedicamentos.periodo_tratamento_inicio',
            'periodo_tratamento_termino' => 'UsuariosMedicamentos.periodo_tratamento_termino',
            'data_hora_uso' => 'UsuariosMedicamentosStatus.data_hora_uso'
        );

        //monta os joins
        $joins = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo and Usuario.ativo = 1',
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                // 'type' => 'INNER',
                'type' => 'LEFT',
                'conditions' => 'UsuariosDados.cpf = Funcionario.cpf',
            ),
            array(
                'table' => 'usuario_sistema',
                'alias' => 'UsuarioSistema',
                'type' => 'INNER',
                'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosDados.codigo_usuario',
            ),
            array(
                'table' => 'usuarios_medicamentos',
                'alias' => 'UsuariosMedicamentos',
                'type' => 'INNER',
                'conditions' => 'UsuarioSistema.codigo_usuario = UsuariosMedicamentos.codigo_usuario',
            ),
            array(
                'table' => 'medicamentos',
                'alias' => 'Medicamentos',
                'type' => 'INNER',
                'conditions' => 'UsuariosMedicamentos.codigo_medicamentos = Medicamentos.codigo',
            ),
            array(
                'table' => 'usuarios_medicamentos_status',
                'alias' => 'UsuariosMedicamentosStatus',
                'type' => 'LEFT',
                'conditions' => 'UsuariosMedicamentos.codigo = UsuariosMedicamentosStatus.codigo_usuario_medicamento',
            ),
            array(
                'table' => 'push_outbox',
                'alias' => 'PushOutbox',
                'type' => 'LEFT',
                'conditions' => 'PushOutbox.foreign_key = UsuariosMedicamentosStatus.codigo',
            ),
        );

        //monta os filtros
        $conditions = array(
            'UsuariosDados.notificacao' => 1,
            'PushOutbox.codigo IS NULL',
            'UsuarioSistema.platform IS NOT NULL',
            'UsuariosMedicamentos.ativo' => 1,
        );

        //GroupBy
        $groupBy = array(
            'UsuarioSistema.token_push',
            'Usuario.codigo',
            'Usuario.nome',
            'Usuario.apelido',
            'Funcionario.cpf',
            'Funcionario.nome',
            'Funcionario.data_nascimento',
            'UsuariosDados.celular',
            'UsuarioSistema.celular',
            'UsuariosDados.telefone',
            'Medicamentos.descricao',
            'UsuarioSistema.platform',
            'UsuariosMedicamentosStatus.codigo',
            'frequencia_dias',
            'frequencia_horarios',
            'uso_continuo',
            'dias_da_semana',
            'frequencia_uso',
            'horario_inicio_uso',
            'frequencia_dias_intercalados',
            'periodo_tratamento_inicio',
            'periodo_tratamento_termino',
            'data_hora_uso',
            'Medicamentos.posologia'
        );

        $dados = $this->find()
            ->select($select)
            ->join($joins)
            ->where($conditions)
            ->group($groupBy)
            ->enableHydration(false)
            ->toArray()
        ;

        return $dados;
    }

    public function getUsuariosMedicamento($codigo) {
        //monta o select
        $select = array(
            'codigo_usuario_medicamento' => 'UsuariosMedicamentos.codigo  ',
            'codigo_usuario' => 'Usuario.codigo',
            'apresentacao' => 'Apresentacoes.descricao',
            'medicamento' => 'CONCAT(Medicamentos.descricao, \' \', Medicamentos.posologia)',
            'usuario_medicamento_status_codigo' => '(SELECT TOP 1 codigo FROM usuarios_medicamentos_status WHERE codigo_usuario_medicamento=UsuariosMedicamentos.codigo)',
            'frequencia_dias' => 'UsuariosMedicamentos.frequencia_dias',
            'frequencia_horarios' => 'UsuariosMedicamentos.frequencia_horarios',
            'quantidade' => 'UsuariosMedicamentos.quantidade',
            'uso_continuo' => 'UsuariosMedicamentos.uso_continuo',
            'dias_da_semana' => 'UsuariosMedicamentos.dias_da_semana',
            'frequencia_uso' => 'UsuariosMedicamentos.frequencia_uso',
            'horario_inicio_uso' => 'UsuariosMedicamentos.horario_inicio_uso',
            'frequencia_dias_intercalados' => 'UsuariosMedicamentos.frequencia_dias_intercalados',
            'periodo_tratamento_inicio' => 'UsuariosMedicamentos.periodo_tratamento_inicio',
            'periodo_tratamento_termino' => 'UsuariosMedicamentos.periodo_tratamento_termino',
            'data_hora_uso' => '(SELECT TOP 1 data_hora_uso FROM usuarios_medicamentos_status WHERE codigo_usuario_medicamento=UsuariosMedicamentos.codigo)'
        );

        //monta os joins
        $joins = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo and Usuario.ativo = 1',
            ),
            array(
                'table' => 'usuarios_medicamentos',
                'alias' => 'UsuariosMedicamentos',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = UsuariosMedicamentos.codigo_usuario AND UsuariosMedicamentos.ativo = 1',
            ),
            array(
                'table' => 'medicamentos',
                'alias' => 'Medicamentos',
                'type' => 'INNER',
                'conditions' => 'UsuariosMedicamentos.codigo_medicamentos = Medicamentos.codigo',
            ),
//            array(
//                'table' => 'usuarios_medicamentos_status',
//                'alias' => 'UsuariosMedicamentosStatus',
//                'type' => 'LEFT',
//                'conditions' => 'UsuariosMedicamentos.codigo = UsuariosMedicamentosStatus.codigo_usuario_medicamento',
//            ),
            array(
                'table' => 'apresentacoes',
                'alias' => 'Apresentacoes',
                'type' => 'LEFT',
                'conditions' => 'UsuariosMedicamentos.codigo_apresentacao = Apresentacoes.codigo',
            ),
        );

        //monta os filtros
        $conditions = array(
            'UsuariosDados.codigo_usuario' => $codigo
        );



        $dados = $this->find()
            ->select($select)
            ->join($joins)
            ->where($conditions)
            ->enableHydration(false)
            ->toArray()
        ;

        return $dados;
    }

    public function getSexoUsuario($codigo_usuario) {
        $select = [
            'sexo' => 'UsuariosDados.sexo'
        ];

        $conditions = [
            'UsuariosDados.codigo_usuario' => $codigo_usuario
        ];

        $dados = $this->find()
            ->select($select)
            ->where($conditions)
            ->enableHydration(false)
            ->first();

        return $dados;
    }

    public function getUsuarioByCPF($cpf)
    {
        //monta o select
        $fields = array(
            'codigo_usuario' => 'Usuario.codigo',
            'usuario_nome'   => 'Usuario.nome',
            'apelido'          => 'Usuario.apelido',
            'senha'          => 'Usuario.senha',
            'cpf'            => 'UsuariosDados.cpf'
        );

        //monta os joins
        $joins = array(
            array(
                'table' => 'usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.codigo_usuario = Usuario.codigo',
            ),
//            array(
//                'table' => 'funcionarios',
//                'alias' => 'Funcionario',
//                'type' => 'INNER',
//                'conditions' => 'UsuariosDados.cpf = Funcionario.cpf',
//            )
        );

        //monta os filtros
        $conditions = array(
            'Usuario.ativo' => 1,
            'UsuariosDados.cpf' => $cpf
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->enableHydration(false)
            ->first();

        return $dados;
    }
}
