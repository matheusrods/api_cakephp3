<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;


/**
 * Profissional Model
 *
 * @property \App\Model\Table\FornecedoresTable&\Cake\ORM\Association\BelongsToMany $Fornecedores
 * @property \App\Model\Table\FuncionariosTable&\Cake\ORM\Association\BelongsToMany $Funcionarios
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 * @property \App\Model\Table\PropostasCredenciamentoTable&\Cake\ORM\Association\BelongsToMany $PropostasCredenciamento
 *
 * @method \App\Model\Entity\Profissional get($primaryKey, $options = [])
 * @method \App\Model\Entity\Profissional newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Profissional[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Profissional|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Profissional saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Profissional patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Profissional[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Profissional findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfissionalTable extends AppTable
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

        $this->setTable('medicos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Fornecedores', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'fornecedore_id',
            'joinTable' => 'fornecedores_medicos'
        ]);
        $this->belongsToMany('Funcionarios', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'funcionario_id',
            'joinTable' => 'funcionarios_medicos'
        ]);
        $this->belongsToMany('Endereco', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'endereco_id',
            'joinTable' => 'medicos_endereco'
        ]);
        $this->belongsToMany('PropostasCredenciamento', [
            'foreignKey' => 'medico_id',
            'targetForeignKey' => 'propostas_credenciamento_id',
            'joinTable' => 'propostas_credenciamento_medicos'
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
            ->scalar('numero_conselho')
            ->maxLength('numero_conselho', 25)
            ->requirePresence('numero_conselho', 'create')
            ->notEmptyString('numero_conselho');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->scalar('conselho_uf')
            ->maxLength('conselho_uf', 2)
            ->allowEmptyString('conselho_uf');

        $validator
            ->integer('codigo_conselho_profissional')
            ->allowEmptyString('codigo_conselho_profissional');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->scalar('especialidade')
            ->maxLength('especialidade', 100)
            ->allowEmptyString('especialidade');

        $validator
            ->scalar('nit')
            ->maxLength('nit', 25)
            ->allowEmptyString('nit');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->scalar('nis')
            ->maxLength('nis', 15)
            ->allowEmptyString('nis');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 14)
            ->allowEmptyString('cpf');

        return $validator;
    }

    /**
     * Pesquisa Profissional
     *
     * @param array $params           parametros de pesquisa
     * @param array $options          utilize para incluir joins e obter mais informações 
     *                                [incluir_fornecedores]
     * @return void
     */
    public function pesquisaProfissional(array $params = [], array $options = [] ){

        $limit = 20;  //quantidade de registros
        $conditions = [];

        $nome = null;
        $numero_conselho = null;
        $conselho_uf = null;
        $retornar_fornecedores = boolval(isset($options['incluir_fornecedores']) && !empty($options['incluir_fornecedores']));

        if(isset($params['search']) && !empty($params['search'])){
            $search = $params['search'];
            $conditions[] = ["Profissional.nome LIKE '%".$search."%' OR Profissional.numero_conselho LIKE '%".$search."%' "];
        }

        if(isset($params['nome']) && !empty($params['nome'])){
            $nome = $params['nome'];
            $conditions[] = ["Profissional.nome LIKE '%".$nome."%'"];
        }

        if(isset($params['numero_conselho']) && !empty($params['numero_conselho'])){
            $numero_conselho = $params['numero_conselho'];
            $conditions[] = ["Profissional.numero_conselho"=> $numero_conselho];
        }

        if(isset($params['crm']) && !empty($params['crm'])){
            $numero_conselho = $params['crm'];
            $conditions[] = ["Profissional.numero_conselho"=> $numero_conselho];
            $conditions[] = ["Profissional.codigo_conselho_profissional"=>1];
        }

        if(isset($params['cro']) && !empty($params['cro'])){
            $numero_conselho = $params['cro'];
            $conditions[] = ["Profissional.numero_conselho"=> $numero_conselho];
            $conditions[] = ["Profissional.codigo_conselho_profissional"=> 6];
        }

        if(isset($params['conselho_uf']) && !empty($params['conselho_uf'])){
            $conselho_uf = $params['conselho_uf'];
            $conditions[] = ["Profissional.conselho_uf "=> $conselho_uf];
        }

        if(isset($params['codigo_profissional']) && !empty($params['codigo_profissional'])){
            $conditions[] = ["Profissional.codigo "=> $params['codigo_profissional']];
        }

        if($retornar_fornecedores && isset($params['codigo_fornecedor']) && !empty($params['codigo_fornecedor'])){
            $conditions[] = ["Fornecedores.codigo "=> $params['codigo_fornecedor']];
        }

        //monta os fields
        $fields = [
            'codigo' => 'Profissional.codigo',
            'nome' => 'Profissional.nome',
            'numero_conselho' => "Profissional.numero_conselho",
            'conselho_uf' => "Profissional.conselho_uf",
            'descricao' => 'ConselhoProfissional.descricao',
        ];

        if($retornar_fornecedores){

            $fields_fornecedores = [
                'fornecedor_codigo' => 'Fornecedores.codigo',
                'fornecedor_nome' => 'Fornecedores.nome'
            ];

            $fields = array_merge($fields,$fields_fornecedores);
        }

        //ligacoes
        $joins = [[
                'table' => 'conselho_profissional',
                'alias' => 'ConselhoProfissional',
                'type' => 'INNER',
                'conditions' => 'Profissional.codigo_conselho_profissional = ConselhoProfissional.codigo'
        ]];

        if($retornar_fornecedores){

            $joins_fornecedores = [[
                'table' => 'fornecedores_medicos',
                'alias' => 'FornecedoresMedicos',
                'type' => 'INNER',
                'conditions' => 'Profissional.codigo = FornecedoresMedicos.codigo_medico'
            ],
            [
                'table' => 'fornecedores',
                'alias' => 'Fornecedores',
                'type' => 'LEFT',
                'conditions' => 'Fornecedores.codigo = FornecedoresMedicos.codigo_fornecedor'
            ]];

            $joins = array_merge($joins,$joins_fornecedores);
        }

        //orderna decrecente
        $order = array('Profissional.codigo DESC');

        $groupby = [
            'Profissional.codigo',
            'Profissional.nome',
            'Profissional.numero_conselho',
            'Profissional.conselho_uf',
            'ConselhoProfissional.descricao',
        ];

        if($retornar_fornecedores){

            $groupby_fornecedores = [
                'Fornecedores.codigo',
                'Fornecedores.nome'
            ];

            $groupby = array_merge($groupby,$groupby_fornecedores);
        }

        try {
            
            //executa a query
            $dados = $this->find()
                        ->select($fields)
                        ->join($joins)
                        ->where($conditions)
                        ->limit($limit)
                        ->group($groupby)
                        ->order($order);
            
        } catch (\Exception $e) {
            $dados = ['error'=> 'Erro na consulta: '.$e->getMessage()];
        }

        if(!$retornar_fornecedores || isset($dados['error'])){
          return $dados;  
        }

        if(!empty($dados)){

            $iconv = new EncodingUtil();

            $resultado = [];
            $resultado_ajustado = [];
            $profissional = [];

            foreach ($dados as $key => $value) {

                $codigo_profissional = $value['codigo'];
                $codigo_fornecedor = $value['fornecedor_codigo'];

                if(!isset($resultado_ajustado[$codigo_profissional])){

                    $fornecedores = [
                        'codigo'=>$value->fornecedor_codigo,
                        'nome'=>$iconv->convert($value->fornecedor_nome)
                    ];

                    unset($value->fornecedor_codigo);
                    unset($value->fornecedor_nome);

                    $profissional = $value;
                    $profissional->fornecedores = [$fornecedores];

                    $resultado_ajustado[$codigo_profissional] = $profissional;
                } else {

                    $fornecedores = [
                        'codigo'=>$value->fornecedor_codigo, 
                        'nome'=>$iconv->convert($value->fornecedor_nome)
                    ];

                    array_push($profissional->fornecedores, $fornecedores);
                }

                array_push($resultado, $profissional);
            }

            $dados = $resultado;
        }
         
        return $dados;
    }

    /**
     * [getMedicosFornecedores metodo para recuperar os medicos pelo pedido do exame pegando o fornecedores 
     * especialmente usado na tela de
     *     fichas_clinicas
     *     ficha_psicossocial
     * ]
     * @param  [type] $codigo_pedido_exame [description]
     * @param  [type] $codigo_exame        [description]
     * @return [type]                      [description]
     */
    public function getMedicosFornecedores($codigo_pedido_exame, $codigo_exame = null)
    {

        // monta o where do exame
        $where = '';
        if(!is_null($codigo_exame)) {
            $where = " AND ItemPedidoExame.codigo_exame = {$codigo_exame}";
        }

        //esta query obtem todos os medicos disponiveis de todos os fornecedores utilizados no pedido de exame formando um unico grupo
        $query_medicos = '
            SELECT Medico.codigo, Medico.nome 
            FROM medicos Medico 
            WHERE Medico.ativo = 1 AND Medico.codigo IN (
                                                        SELECT FornecedorMedico.codigo_medico 
                                                        FROM fornecedores_medicos FornecedorMedico 
                                                        WHERE FornecedorMedico.codigo_fornecedor IN (
                                                            SELECT ItemPedidoExame.codigo_fornecedor 
                                                            FROM itens_pedidos_exames ItemPedidoExame 
                                                            WHERE ItemPedidoExame.codigo_pedidos_exames = '.$codigo_pedido_exame . $where .'
                                                            )
                                                        ) 
            ';
        //executa a query dos medicos relacionados para este fornecedor
        $conn = ConnectionManager::get('default');
        $medicos =  $conn->execute($query_medicos)->fetchAll('assoc');

        $values = array();
        if(!empty($medicos)) {
            foreach ($medicos as $key => $medico) {
                $values[$medico['codigo']] = $medico['nome'];
            }//fim foreach medicos
        }

        return $values;

    }// fim getMedicosFornecedores

}
