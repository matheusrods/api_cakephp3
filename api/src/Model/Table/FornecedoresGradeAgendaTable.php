<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FornecedoresGradeAgenda Model
 *
 * @method \App\Model\Entity\FornecedoresGradeAgenda get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresGradeAgenda findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresGradeAgendaTable extends AppTable
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

        $this->setTable('fornecedores_grade_agenda');
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
            ->requirePresence('dia_semana', 'create')
            ->notEmptyString('dia_semana');

        $validator
            ->requirePresence('hora', 'create')
            ->notEmptyString('hora');

        $validator
            ->requirePresence('capacidade_simultanea', 'create')
            ->notEmptyString('capacidade_simultanea');

        $validator
            ->requirePresence('tempo_consulta', 'create')
            ->notEmptyString('tempo_consulta');

        $validator
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_lista_de_preco_produto_servico')
            ->allowEmptyString('codigo_lista_de_preco_produto_servico');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }

    /**
     * [retorna_grade_especifica description]
     * 
     * metodo para pegar a grade de agenda do fornecedor para aquele servico/exame
     * 
     * @param  [type] $codigo_fornecedor [description]
     * @param  [type] $codigo_servico    [description]
     * @return [type]                    [description]
     */
    public function retorna_grade_especifica($codigo_fornecedor, $codigo_servico) 
    {
        //campos para a busca na tabela
        $fields = array(
            'ListaDePrecoProdutoServico.codigo_servico',
            'ListaDePrecoProdutoServico.codigo'
        );
        //monta o relacionamento
        $join  = array(
            array(
                'table' => 'listas_de_preco_produto_servico',
                'alias' => 'ListaDePrecoProdutoServico',
                'type' => 'LEFT',
                'conditions' => 'ListaDePrecoProdutoServico.codigo = FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico',
            ),
        );
        //monta o filtro
        $conditions = array(
            'FornecedoresGradeAgenda.codigo_fornecedor' => $codigo_fornecedor,
            'ListaDePrecoProdutoServico.codigo_servico' => $codigo_servico
        );      
        //ordena a query
        $order = array('FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico DESC');
        //executa
        $dados = $this->find()
                      ->select($fields)
                      ->join($join)
                      ->where($conditions)
                      ->order($order)
                      ->hydrate(false)
                      ->first();

        return $dados;

    }//fim retorna_grade_especifica

    /**
     * [retorna_agenda_especifica description]
     * 
     * metodo praa pegar a agenda do prestador 
     * 
     * @param  [type] $codigo_fornecedor [description]
     * @param  [type] $codigo_servico    [description]
     * @return [type]                    [description]
     */
    public function retorna_agenda_especifica($codigo_fornecedor, $codigo_servico) 
    {
        
        $fields = array(
            'FornecedoresGradeAgenda.codigo',
            'FornecedoresGradeAgenda.dia_semana',
            'FornecedoresGradeAgenda.hora',
            'FornecedoresGradeAgenda.capacidade_simultanea',
            'FornecedoresGradeAgenda.tempo_consulta',
            'FornecedoresGradeAgenda.codigo_fornecedor',
            'FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico'
        );
         
        $joins  = array(
            array(
                'table' => 'listas_de_preco',
                'alias' => 'ListaDePreco',
                'type' => 'INNER',
                'conditions' => 'ListaDePreco.codigo_fornecedor = FornecedoresGradeAgenda.codigo_fornecedor',
            ),
            array(
                'table' => 'listas_de_preco_produto',
                'alias' => 'ListaDePrecoProduto',
                'type' => 'INNER',
                'conditions' => 'ListaDePrecoProduto.codigo_lista_de_preco = ListaDePreco.codigo',
            ),              
            array(
                'table' => 'listas_de_preco_produto_servico',
                'alias' => 'ListaDePrecoProdutoServico',
                'type' => 'INNER',
                'conditions' => 'ListaDePrecoProdutoServico.codigo_lista_de_preco_produto = ListaDePrecoProduto.codigo AND ListaDePrecoProdutoServico.codigo = FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico',
            ),
        );
        
        $order = array('FornecedoresGradeAgenda.dia_semana ASC', 'FornecedoresGradeAgenda.hora ASC');
        // $group = array(  
        //     'FornecedoresGradeAgenda.dia_semana',
        //     'FornecedoresGradeAgenda.hora',
        //     'FornecedoresGradeAgenda.capacidade_simultanea',
        //     'FornecedoresGradeAgenda.tempo_consulta',
        //     'FornecedoresGradeAgenda.codigo_fornecedor',
        //     'FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico'
        // );
     
        $conditions = array(
            'FornecedoresGradeAgenda.codigo_fornecedor' => $codigo_fornecedor,
            'ListaDePrecoProdutoServico.codigo_servico' => $codigo_servico
        );          
        
        //executa
        $dados = $this->find()
                      ->select($fields)
                      ->join($joins)
                      ->where($conditions)
                      // ->group($group)
                      ->order($order)
                      ->hydrate(false)
                      ->toArray();

        // debug($dados->sql());exit;

        return $dados;
    }
    
    function retorna_agenda_padrao($codigo_fornecedor, $codigo_agenda) {
    
        $options['fields'] = array(
                'FornecedoresGradeAgenda.dia_semana',
                'FornecedoresGradeAgenda.hora',
                'FornecedoresGradeAgenda.capacidade_simultanea',
                'FornecedoresGradeAgenda.tempo_consulta',
                'FornecedoresGradeAgenda.codigo_fornecedor',
                'FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico'
        );
            
        $options['joins']  = array(
                array(
                        'table' => 'listas_de_preco',
                        'alias' => 'ListaDePreco',
                        'type' => 'LEFT',
                        'conditions' => 'ListaDePreco.codigo_fornecedor = FornecedoresGradeAgenda.codigo_fornecedor',
                ),
                array(
                        'table' => 'listas_de_preco_produto',
                        'alias' => 'ListaDePrecoProduto',
                        'type' => 'LEFT',
                        'conditions' => 'ListaDePrecoProduto.codigo_lista_de_preco = ListaDePreco.codigo',
                ),
                array(
                        'table' => 'listas_de_preco_produto_servico',
                        'alias' => 'ListaDePrecoProdutoServico',
                        'type' => 'LEFT',
                        'conditions' => 'ListaDePrecoProdutoServico.codigo_lista_de_preco_produto = ListaDePrecoProduto.codigo AND ListaDePrecoProdutoServico.codigo = FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico',
                ),
        );
    
        $options['order'] = array('FornecedoresGradeAgenda.dia_semana ASC', 'FornecedoresGradeAgenda.hora ASC');
        $options['group'] = array(
                'FornecedoresGradeAgenda.dia_semana',
                'FornecedoresGradeAgenda.hora',
                'FornecedoresGradeAgenda.capacidade_simultanea',
                'FornecedoresGradeAgenda.tempo_consulta',
                'FornecedoresGradeAgenda.codigo_fornecedor',
                'FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico'
        );
    
        $options['conditions'] = array(
                'FornecedoresGradeAgenda.codigo_fornecedor' => $codigo_fornecedor,
                'FornecedoresGradeAgenda.codigo_lista_de_preco_produto_servico' => $codigo_agenda
        );
    
        return $this->find('all', $options);
    }   


}
