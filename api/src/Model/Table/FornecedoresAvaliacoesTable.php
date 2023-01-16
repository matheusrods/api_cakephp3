<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FornecedoresAvaliacoes Model
 *
 * @method \App\Model\Entity\FornecedoresAvaliaco get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresAvaliaco findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresAvaliacoesTable extends AppTable
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

        $this->setTable('fornecedores_avaliacoes');
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
            ->integer('codigo_fornecedor')
            ->allowEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_fornecedor_tipo_avaliacao')
            ->allowEmptyString('codigo_fornecedor_tipo_avaliacao');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->integer('codigo_item_pedido_exame')
            ->allowEmptyString('codigo_item_pedido_exame');

        $validator
            ->integer('avaliacao')
            ->allowEmptyString('avaliacao');

        $validator
            ->scalar('comentario')
            ->allowEmptyString('comentario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        return $validator;
    }

    /**
     * Busca a nota de um fornecedor
     * @param $codigo_fornecedor
     * @return array|void
     */
    public function getFornecedorNota($codigo_fornecedor){
        $select = [
            "FornecedoresAvaliacoes.codigo_fornecedor",
            "FornecedoresAvaliacoes.codigo_fornecedor_tipo_avaliacao",
            "FornecedoresAvaliacoes.avaliacao",
            "FornecedoresTipoAvaliacao.descricao",
            "total" => "COUNT(FornecedoresAvaliacoes.codigo)",
            "quantidade" => "COUNT(FornecedoresAvaliacoes.codigo)"
        ];
        $joins = array(
            array(
                'table' => 'fornecedores_tipo_avaliacao',
                'alias' => 'FornecedoresTipoAvaliacao',
                'type' => 'INNER',
                'conditions' => 'FornecedoresTipoAvaliacao.codigo = FornecedoresAvaliacoes.codigo_fornecedor_tipo_avaliacao',
            ),
        );
        $where =["codigo_fornecedor"=>$codigo_fornecedor];
        $group_by = ["FornecedoresAvaliacoes.codigo_fornecedor", "FornecedoresAvaliacoes.codigo_fornecedor_tipo_avaliacao", "FornecedoresAvaliacoes.avaliacao", "FornecedoresTipoAvaliacao.descricao"];

        $result = $this->find()->select($select)->where($where)->join($joins)->group($group_by);

        // Inicializa as variáveis
        $somatorioTipoAvaliacao = [];
        $n = [];
        $universo = 0;

        // Somatório e espaço amostral
        foreach($result as $avaliacao){

            // criando indices
            if(!isset($somatorioTipoAvaliacao[$avaliacao->FornecedoresTipoAvaliacao['descricao']])){
                $somatorioTipoAvaliacao[$avaliacao->FornecedoresTipoAvaliacao['descricao']] = 0;
                $n[$avaliacao->FornecedoresTipoAvaliacao['descricao']] = 0;
            }

            // Somatório
            $somatorioTipoAvaliacao[$avaliacao->FornecedoresTipoAvaliacao['descricao']] += $avaliacao->avaliacao*$avaliacao->total;

            // espaço amostral
            $n[$avaliacao->FornecedoresTipoAvaliacao['descricao']] += $avaliacao->total;

        }

        $media_ponderada = [];
        $media_ponderada_total = 0;
        $universo_total = 0;

        // Obtendo médias
        foreach($somatorioTipoAvaliacao as $key => $somatorio){
            if(!isset($media_ponderada[$key])) {
                $media_ponderada[$key] = 0;
            }
            if($n[$key] == 0){
                $media_ponderada[$key] = 0;
            } else {
                $universo_total += $n[$key];
                $media_ponderada[$key] = [
                    "pontuacao_arredondada" => round($somatorioTipoAvaliacao[$key] / $n[$key],1),
                    "pontuacao" => $somatorioTipoAvaliacao[$key] / $n[$key],
                    "quantidade_avaliacoes" => $n[$key]
                ];
            }
            $media_ponderada_total += $media_ponderada[$key]['pontuacao'];
        }
        if(sizeof($somatorioTipoAvaliacao) > 0){
            $media_ponderada_total = $media_ponderada_total / sizeof($somatorioTipoAvaliacao);
        } else {
            return null;
        }

        return [
            "pontuacao_arredondada" =>  round($media_ponderada_total,1),
            "pontuacao" =>  $media_ponderada_total,
            "quantidade_avaliacoes" => $universo_total,
            "detalhado" => $media_ponderada
        ];
    }

}
