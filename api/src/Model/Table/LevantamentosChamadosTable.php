<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LevantamentosChamados Model
 *
 * @method \App\Model\Entity\LevantamentosChamado get($primaryKey, $options = [])
 * @method \App\Model\Entity\LevantamentosChamado newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LevantamentosChamado[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LevantamentosChamado|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LevantamentosChamado saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LevantamentosChamado patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LevantamentosChamado[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LevantamentosChamado findOrCreate($search, callable $callback = null, $options = [])
 */
class LevantamentosChamadosTable extends Table
{
    const NAO_INICIADO = 1;
    const ADIADO = 2;
    const EM_ANDAMENTO = 3;
    const CONCLUIDO = 4;
    const CANCELADO = 5;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('levantamentos_chamados');
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
            ->integer('codigo_chamado')
            ->requirePresence('codigo_chamado', 'create')
            ->notEmptyString('codigo_chamado');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_adiamento')
            ->allowEmptyDateTime('data_adiamento');

        $validator
            ->scalar('observacao')
            ->maxLength('observacao', 255)
            ->allowEmptyString('observacao');

        $validator
            ->integer('codigo_levantamento_chamado_status')
            ->allowEmptyString('codigo_levantamento_chamado_status');

        $validator
            ->integer('codigo_usuario_gestor_operacao')
            ->allowEmptyString('codigo_usuario_gestor_operacao');

        $validator
            ->integer('codigo_usuario_tecnico_ehs')
            ->allowEmptyString('codigo_usuario_tecnico_ehs');

        $validator
            ->integer('codigo_usuario_operador')
            ->allowEmptyString('codigo_usuario_operador');

        $validator
            ->dateTime('data_inicio_avaliacao')
            ->allowEmptyDateTime('data_inicio_avaliacao');

        $validator
            ->dateTime('data_fim_avaliacao')
            ->allowEmptyDateTime('data_fim_avaliacao');

        $validator
            ->scalar('companheiro_avaliacao')
            ->maxLength('companheiro_avaliacao', 255)
            ->allowEmptyString('companheiro_avaliacao');

        $validator
            ->scalar('descricao_avaliacao')
            ->maxLength('descricao_avaliacao', 255)
            ->allowEmptyString('descricao_avaliacao');

        $validator
            ->integer('nota_avaliacao')
            ->allowEmptyString('nota_avaliacao')
            ->range('nota_avaliacao', [0, 5], 'O valor fornecido deve ser entre 0 e 5');

        $validator
            ->scalar('descricao')
            ->allowEmptyString('descricao');

        return $validator;
    }

    public function getDadosEmpresa($codigo_cliente)
    {
        //Condições para retornar os dados da empresa (ENDEREÇO)
        $fieldsEndereco = array(
            'complemento'  => 'ClienteEndereco.complemento',
            'numero'     => 'ClienteEndereco.numero',
            'cep'    => 'ClienteEndereco.cep',
            'logradouro'    => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.logradouro)',
            'bairro'    => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.bairro)',
            'cidade'    => 'RHHealth.dbo.ufn_decode_utf8_string(ClienteEndereco.cidade)',
            'estado_descricao'    => 'ClienteEndereco.estado_descricao',
            'estado_abreviacao'    => 'ClienteEndereco.estado_abreviacao'
        );

        $joinsEndereco  = array(
            array(
                'table' => 'cliente_endereco',
                'alias' => 'ClienteEndereco',
                'type'  => 'INNER',
                'conditions' => 'LevantamentosChamados.codigo_cliente = ClienteEndereco.codigo_cliente',
            )
        );

        $conditions = " LevantamentosChamados.codigo_cliente = " . $codigo_cliente . " " ;

        $dadosEndereco = $this->find()
            ->select($fieldsEndereco)
            ->join($joinsEndereco)
            ->where($conditions)
            ->first();

        //Condições para retornar o responsavel da empresa

        $fieldsContato = array(
            'responsavel'  => 'ClienteContato.nome',
            'razao_social'  => 'Cliente.razao_social',
        );

        $joinsContato  = array(
            array(
                'table' => 'cliente_contato',
                'alias' => 'ClienteContato',
                'type'  => 'INNER',
                'conditions' => 'LevantamentosChamados.codigo_cliente = ClienteContato.codigo_cliente',
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type'  => 'INNER',
                'conditions' => 'LevantamentosChamados.codigo_cliente = Cliente.codigo',
            )
        );

        $conditionsContato = " LevantamentosChamados.codigo_cliente = " . $codigo_cliente . " " ;

        $dadosContato = $this->find()
            ->select($fieldsContato)
            ->join($joinsContato)
            ->where($conditionsContato)
            ->first();

        $arrayEmpresa = array();
        $arrayEmpresa['razao_social'] = $dadosContato['razao_social'];
        $arrayEmpresa['responsavel'] = $dadosContato['responsavel'];
        $arrayEmpresa['endereco'] = $dadosEndereco;

        return $arrayEmpresa;
    }

    public function getLevantamentosPorResponsavel($codigo_cliente, $codigo_responsavel, $pendente = null)
    {
        $where = [
            'LevantamentosChamados.codigo_cliente' => $codigo_cliente,
            'Chamados.responsavel' => $codigo_responsavel
        ];

        if($pendente){
            $where[] = "LevantamentosChamados.codigo_levantamento_chamado_status NOT IN (" . self::CONCLUIDO . ", " . self::CANCELADO . ")";
        }

        $query = $this->find()
            ->join([
                'Chamados' => [
                    'table' => 'chamados',
                    'alias' => 'Chamados',
                    'type' => 'INNER',
                    'conditions' => ['Chamados.codigo = LevantamentosChamados.codigo_chamado'],
                ],
            ])
            ->where($where);

        // debug($query->sql()); die;
        return $query->all()->toArray();
    }
}
