<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * ClienteProduto Model
 *
 * @property \App\Model\Table\ServicoTable&\Cake\ORM\Association\BelongsToMany $Servico
 * @property \App\Model\Table\Servico2Table&\Cake\ORM\Association\BelongsToMany $Servico2
 * @property \App\Model\Table\ServicoLogTable&\Cake\ORM\Association\BelongsToMany $ServicoLog
 *
 * @method \App\Model\Entity\ClienteProduto get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteProduto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteProduto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProduto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteProduto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteProduto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProduto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteProduto findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteProdutoTable extends AppTable
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

        $this->setTable('cliente_produto');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Servico', [
            'foreignKey' => 'cliente_produto_id',
            'targetForeignKey' => 'servico_id',
            'joinTable' => 'cliente_produto_servico'
        ]);
        $this->belongsToMany('Servico2', [
            'foreignKey' => 'cliente_produto_id',
            'targetForeignKey' => 'servico2_id',
            'joinTable' => 'cliente_produto_servico2'
        ]);
        $this->belongsToMany('ServicoLog', [
            'foreignKey' => 'cliente_produto_id',
            'targetForeignKey' => 'servico_log_id',
            'joinTable' => 'cliente_produto_servico_log'
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
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->requirePresence('codigo_produto', 'create')
            ->notEmptyString('codigo_produto');

        $validator
            ->dateTime('data_faturamento')
            ->allowEmptyDateTime('data_faturamento');

        $validator
            ->requirePresence('codigo_motivo_bloqueio', 'create')
            ->notEmptyString('codigo_motivo_bloqueio');

        $validator
            ->boolean('possui_contrato')
            ->notEmptyString('possui_contrato');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('qtd_premio_minimo')
            ->allowEmptyString('qtd_premio_minimo');

        $validator
            ->numeric('valor_premio_minimo')
            ->notEmptyString('valor_premio_minimo');

        $validator
            ->decimal('valor_taxa_corretora')
            ->allowEmptyString('valor_taxa_corretora');

        $validator
            ->decimal('valor_taxa_bancaria')
            ->allowEmptyString('valor_taxa_bancaria');

        $validator
            ->dateTime('data_inativacao')
            ->allowEmptyDateTime('data_inativacao');

        $validator
            ->boolean('pendencia_comercial')
            ->allowEmptyString('pendencia_comercial');

        $validator
            ->boolean('pendencia_financeira')
            ->allowEmptyString('pendencia_financeira');

        $validator
            ->boolean('pendencia_juridica')
            ->allowEmptyString('pendencia_juridica');

        $validator
            ->integer('codigo_motivo_bloqueio_bkp')
            ->allowEmptyString('codigo_motivo_bloqueio_bkp');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('premio_minimo_por_produto')
            ->notEmptyString('premio_minimo_por_produto');

        $validator
            ->integer('codigo_motivo_bloqueio_bkp2')
            ->allowEmptyString('codigo_motivo_bloqueio_bkp2');

        $validator
            ->integer('codigo_motivo_cancelamento')
            ->allowEmptyString('codigo_motivo_cancelamento');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    /**
     * [listarPorCodigoCliente description]
     * 
     * pega os produtos que o cliente contratou
     * 
     * @param  [type]  $codigo_cliente             [description]
     * @param  boolean $teleconsult                [description]
     * @param  boolean $listar_motivo_cancelamento [description]
     * @return [type]                              [description]
     */
    public function listarPorCodigoCliente($codigo_cliente, $servico_not_in = false, $somente_exame_complementar = false) 
    {
        
        //instancia a class
        $this->ClienteProdutoServico2 = TableRegistry::get('ClienteProdutoServico2');
         
        //join da query
        $joins_cs2 = array(
            array(
                'table' => 'produto',
                'alias' => 'Produto',
                'type' => 'INNER',
                'conditions' => 'Produto.codigo = ClienteProduto.codigo_produto',
            ),
        );

        $selectCP = array(
            'ClienteProduto.codigo',
            'ClienteProduto.codigo_produto',
            'Produto.codigo',
            'Produto.descricao'            
        );
        
        //executa a query
        $linhas = $this->find()
                        ->select($selectCP)
                        ->join($joins_cs2)
                        ->where(['ClienteProduto.codigo_cliente' => $codigo_cliente])
                        ->hydrate(false)
                        ->toArray();
        
        // debug($codigo_cliente);debug($linhas->sql());exit;
        // dd($linhas,$codigo_cliente);exit;
        
        // verifica se existe dados de relacionamento de cliente com produtos 
        // pois pode ser uma alocacao que não tenha produto relacionado
        if(!empty($linhas)) {
        
            //verifica se tem este parametros
            $conditionsServico = array();
            if($servico_not_in) {
                $servico_not_in = implode(",", $servico_not_in);
                
                //caso tenha, nao ira mostrar os dados destes servicos
                $conditionsServico = array('ClienteProdutoServico2.codigo_servico NOT IN ('.$servico_not_in.')');
            }//fim servico_not_in
            
            //varre os produtos que o cliente contratou
            foreach($linhas as $key => $linha) {

                //campos da query
                $fields = array(
                    'ClienteProdutoServico2.codigo',
                    'ClienteProdutoServico2.codigo_servico',
                    'ClienteProdutoServico2.valor',
                    'ClienteProdutoServico2.codigo_cliente_pagador',                
                    'Servico.codigo',
                    'Servico.descricao',
                    'Servico.tipo_servico',
                    'Servico.codigo_externo',
                    'ProdutoServico.codigo',
                    'ProdutoServico.codigo_produto',
                    'ProdutoServico.codigo_servico',
                    'credenciados' => '(  SELECT count(*) 
                                        FROM listas_de_preco_produto_servico LPPS
                                            INNER JOIN listas_de_preco_produto LPP 
                                                ON(LPP.codigo = LPPS.codigo_lista_de_preco_produto)
                                            INNER JOIN listas_de_preco LP
                                                ON(LP.codigo = LPP.codigo_lista_de_preco)
                                            INNER JOIN clientes_fornecedores CF
                                                ON(CF.codigo_fornecedor = LP.codigo_fornecedor AND CF.ativo = 1)
                                        WHERE LPPS.codigo_servico = ClienteProdutoServico2.codigo_servico AND CF.codigo_cliente = ClienteProdutoServico2.codigo_cliente_pagador)'
                );

                //join da query
                $joins = array(
                    array(
                        'table' => 'servico',
                        'alias' => 'Servico',
                        'type' => 'INNER',
                        'conditions' => 'Servico.codigo = ClienteProdutoServico2.codigo_servico',
                    ),
                    array(
                        'table' => 'produto_servico',
                        'alias' => 'ProdutoServico',
                        'type' => 'INNER',
                        'conditions' => 'Servico.codigo = ProdutoServico.codigo_servico AND ProdutoServico.codigo_produto = ' . $linha['codigo_produto'],
                    ),
                );

                //filtros
                $conditions = array(
                    'ClienteProdutoServico2.codigo_cliente_produto' => $linha['codigo'],
                    'Servico.ativo' => 1, //SERVIÇO TEM QUE ESTAR ATIVO
                    $conditionsServico
                );

                //executa a query
                $retornos = $this->ClienteProdutoServico2->find('all')
                                                         ->select($fields)
                                                         ->join($joins)
                                                         ->where($conditions)
                                                         ->order(['Servico.descricao'])
                                                         ->hydrate(false)
                                                         ->toArray();

                // debug(array($conditions,$retornos->sql()));continue;//exit;

                //monta o retorno
                if($somente_exame_complementar) {
                    if(count($retornos) > 0 && $linha['codigo_produto'] == 59){ //exames complementares
                        $cliente_produto_servico = array();
                        $clienteProdutoServico = array();
                        foreach($retornos as $posicao => $retorno){

                            // debug($retorno['Servico']);

                            $clienteProdutoServico['ClienteProdutoServico2']['codigo'] = $retorno['codigo'];
                            $clienteProdutoServico['ClienteProdutoServico2']['codigo_servico'] = $retorno['codigo_servico'];
                            $clienteProdutoServico['ClienteProdutoServico2']['valor'] = $retorno['valor'];
                            $clienteProdutoServico['ClienteProdutoServico2']['codigo_cliente_pagador'] = $retorno['codigo_cliente_pagador'];
                            $clienteProdutoServico['ClienteProdutoServico2']['credenciados'] = $retorno['credenciados'];

                            $cliente_produto_servico[$posicao] = $clienteProdutoServico;
                            $cliente_produto_servico[$posicao]['Servico'] = $retorno['Servico'];
                            $cliente_produto_servico[$posicao]['ProdutoServico'] = $retorno['ProdutoServico'];
                        }

                        $linhas[$key]['ClienteProdutoServico2'] = $cliente_produto_servico;

                    }else{
                        unset($linhas[$key]);
                    }
                
                }
                else {

                    // debug($retornos);

                    if(count($retornos) > 0){
                        $cliente_produto_servico = array();
                        $clienteProdutoServico = array();
                        foreach($retornos as $posicao => $retorno){

                            // debug($retorno['Servico']);

                            $clienteProdutoServico['ClienteProdutoServico2']['codigo'] = $retorno['codigo'];
                            $clienteProdutoServico['ClienteProdutoServico2']['codigo_servico'] = $retorno['codigo_servico'];
                            $clienteProdutoServico['ClienteProdutoServico2']['valor'] = $retorno['valor'];
                            $clienteProdutoServico['ClienteProdutoServico2']['codigo_cliente_pagador'] = $retorno['codigo_cliente_pagador'];
                            $clienteProdutoServico['ClienteProdutoServico2']['credenciados'] = $retorno['credenciados'];

                            $cliente_produto_servico[$posicao] = $clienteProdutoServico;
                            $cliente_produto_servico[$posicao]['Servico'] = $retorno['Servico'];
                            $cliente_produto_servico[$posicao]['ProdutoServico'] = $retorno['ProdutoServico'];
                        }

                        $linhas[$key]['ClienteProdutoServico2'] = $cliente_produto_servico;

                    }else{                        
                        unset($linhas[$key]);
                    }
                }

            
            }//fim foreach

        }//fim verificacao linhas

        // debug($linhas);
        // exit;

        return $linhas;
    
    }//fim listaPorCliente

}
