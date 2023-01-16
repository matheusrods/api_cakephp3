<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use App\Utils\DatetimeUtil;

/**
 * UsuariosAbdominal Model
 *
 * @method \App\Model\Entity\UsuariosAbdominal get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAbdominal findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosAbdominalTable extends AppTable
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

        $this->setTable('usuarios_abdominal');
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
            // ->requirePresence('codigo', 'create')
            // ->notEmptyString('codigo');

        $validator
            ->integer('circ_abdom')
            ->allowEmptyString('circ_abdom');

        $validator
            ->integer('circ_quadril')
            ->allowEmptyString('circ_quadril');

        $validator
            ->numeric('circ_media')
            ->allowEmptyString('circ_media');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    /**
     * [getHistoricoAbdominal description]
     *
     * ,metodo para pegar os dados historicos de grafico para abdominal
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getHistoricoAbdominal($codigo_usuario,$sexo)
    {

        $conditions = array('codigo_usuario' => $codigo_usuario);

        $order = 'data_inclusao desc';
        $limit = '10';

        $fields = array(
            'codigo' => 'codigo',
            'circ_abdom' => "circ_abdom",
            'circ_quadril'=>"circ_quadril",
            'circ_media'=>"circ_media",
            'data_inclusao'=>"data_inclusao",
            'ANO'=>"YEAR(data_inclusao)",
            'MES'=>"REPLICATE('0',2 - LEN(CAST(MONTH(data_inclusao) AS VARCHAR(2)))) + CAST(MONTH(data_inclusao) AS VARCHAR(2))",
            'DIA'=>"REPLICATE('0',2 - LEN(CAST(DAY(data_inclusao) AS VARCHAR(2)))) + CAST(DAY(data_inclusao) AS VARCHAR(2))",
            'HORAS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2))",
            'MINUTOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2))",
            'SEGUNDOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2))"
        );

        //pega os dados da consulta
        $dados_abdominal = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        $grafico_circ_media = array();

        foreach ($dados_abdominal as $dados){

            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;

            $resultadoAbdomen = $this->getResultadoAbdominal($dados['circ_media'],$sexo);
            $codigo_cor = $resultadoAbdomen['codigo_cor'];
            $resultado = $resultadoAbdomen['texto'];

            $grafico_circ_media[]  = array(
                'codigo' => $dados['codigo'],
                'timestamp' => $timestamp,

                'data_inclusao' => $dados->data_inclusao,

                'resultado' => array(
                    'valor' => round($dados['circ_media'],2),
                    'label' => $resultado,
                ),
                'label' => array(
                    //Alguns indicadores exibe um label colorido
                    'codigo_cor' => $codigo_cor,
                    'texto' => $resultado,
                ),

                'campos' => array(
                    array(
                        'nome' => 'circ_abdom',
                        'label' => ' Circunferência abdominal',
                        'type' => 'int',
                        'maxlength' => '3',
                        'value' => round($dados['circ_abdom'],2),
                        'show' => false
                    ),
                    array(
                        'nome' => 'circ_quadril',
                        'label' => 'Circunferência quadril',
                        'type' => 'int',
                        'maxlength' => '3',
                        'value' => round($dados['circ_quadril'],2),
                        'show' => false
                    ),
                    array(
                        'nome' => 'circ_media',
                        'value' => round($dados['circ_media'],2),
                        'label' => 'Circunferência Média',
                        'show' => true
                    ),

                )
            );

            // $grafico_circ_media[] = array(
            //                             'timestamp' => ($timestamp - 10800)*1000,
            //                             'data_inclusao' => $dados->data_inclusao,
            //                             'valor' =>$dados['circ_media']
            //                         );
        }

        return $grafico_circ_media;

    }//fim getHistoricoAbdominal

     /**
     * [getResultadoAbdominal description]
     *
     * calcula a cor que irá utilizar para atualizacao
     *
     * @param  $resultado [description]
     * @return [type]             [description]
     */
    public function getResultadoAbdominal($resultado,$sexo)
    {
        $codigo_cor = 4; //vermelho
        $texto = '';
        $percentual = 0;

        if(!empty($resultado)){

            // Postar no Wrike
            if($sexo == "M") {

                if($resultado < 0.91){
                    $codigo_cor = 1; //verde
                    $texto = 'Baixo';
                    $percentual = 15; // valores fixados por solicitacao - dev. app.
                } else if ($resultado >= 0.91 && $resultado < 0.98){
                    $codigo_cor = 2; //amarelo
                    $texto = 'Moderado';
                    $percentual = 50;
                } else if ($resultado >= 0.99 && $resultado < 1.03){
                    $codigo_cor = 3; //laranja
                    $texto = 'Alto';
                    $percentual = 50;
                } else if ($resultado > 1.03){
                    $codigo_cor = 4; //vermelho
                    $texto = 'Muito alto';
                    $percentual = 100;
                    $percentual = 90;
                }
            }
            else if($sexo == "F"){

                if($resultado < 0.76){
                    $codigo_cor = 1; //verde
                    $texto = 'Baixo';
                    $percentual = 15;
                } else if ($resultado >= 0.76 && $resultado < 0.83){
                    $codigo_cor = 2; //amarelo
                    $texto = 'Moderado';
                    $percentual = 50;
                } else if ($resultado >= 0.84 && $resultado < 0.90){
                    $codigo_cor = 3; //laranja
                    $texto = 'Alto';
                    $percentual = 50;
                } else if ($resultado > 0.90){
                    $codigo_cor = 4; //vermelho
                    $texto = 'Muito alto';
                    $percentual = 90;
                }
            }

        }//fim if resultado

        $results['codigo_cor'] = $codigo_cor;
        $results['texto'] = $texto;
        $results['percentual'] = $percentual;

        return $results;

    }//fim getResultadoAbdominal

    /**
     * [montaLabelsGrafico description]
     *
     * monta o objeto de labels grafico
     *
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function montaLabelsGrafico($codigo_usuario)
    {

        //monta os filtros
        $conditions = array('codigo_usuario' => $codigo_usuario);

        $order = 'data_inclusao asc';
        $limit = '10';

        //campos da tabela
        $fields = array(
                'codigo' => 'codigo',
                'circ_abdom' => "circ_abdom",
                'circ_quadril'=>"circ_quadril",
                'circ_media'=>"circ_media",
                'data_inclusao'=>"data_inclusao",
            );
        //pega os dados da consulta
        $dados_grafico = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $grafico = array();

        foreach ($dados_grafico as $dados){

            $grafico[]  = array(
                'name' => "circ_media",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.
                'value' => round($dados['circ_media'],2),
            );

        }//fim foreach

        //retorna o objeto
        return $grafico;


    } //fim montaLabelsGrafico


}
