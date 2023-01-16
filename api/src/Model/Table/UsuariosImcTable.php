<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use App\Utils\DatetimeUtil;

/**
 * UsuariosImc Model
 *
 * @method \App\Model\Entity\UsuariosImc get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosImc newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosImc[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosImc|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosImc saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosImc patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosImc[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosImc findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosImcTable extends AppTable
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

        $this->setTable('usuarios_imc');
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
            ->integer('altura')
            ->allowEmptyString('altura');

        $validator
            ->numeric('peso')
            ->allowEmptyString('peso');

        $validator
            ->numeric('resultado')
            ->allowEmptyString('resultado');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    /**
     * [getHistoricoImc description]
     *
     * metodo para pegar os dados historico do imc
     *
     * @return [type] [description]
     */
    public function getHistoricoImc($codigo_usuario)
    {

        $conditions = array('codigo_usuario' => $codigo_usuario);

        $order = 'data_inclusao desc';
        $limit = '10';

        $fields = array(
            'codigo' => 'codigo',
            'altura'=>"altura",
            'peso'=>"peso",
            'resultado'=>"resultado",
            'data_inclusao' => "data_inclusao",
            'ANO'=>"YEAR(data_inclusao)",
            'MES'=>"REPLICATE('0',2 - LEN(CAST(MONTH(data_inclusao) AS VARCHAR(2)))) + CAST(MONTH(data_inclusao) AS VARCHAR(2))",
            'DIA'=>"REPLICATE('0',2 - LEN(CAST(DAY(data_inclusao) AS VARCHAR(2)))) + CAST(DAY(data_inclusao) AS VARCHAR(2))",
            'HORAS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2))",
            'MINUTOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2))",
            'SEGUNDOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2))"
        );

        //pega os dados da consulta
        $dados_imc = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        // dd($dados_imc);

        // $grafico_altura = array();
        // $grafico_peso = array();
        $historico = array();

        foreach ($dados_imc as $dados){

            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;

            $retorno_imc = $this->getResultadoImc($dados['resultado']);
            $resultado = $retorno_imc['texto'];

            $historico[]  = array(
                'codigo' => $dados['codigo'],
                'timestamp' => $timestamp,

                'data_inclusao' => $dados->data_inclusao,

                'resultado' => array(
                    'valor' => round($dados['resultado'],2),
                    'label' => $resultado
                ),
                'label' => array(),

                'campos' => array(
                    array(
                        'nome' => 'altura',
                        'value' => round($dados['altura'],2),
                        'label' => 'Altura (cm)',
                        'show'  => true
                    ),
                    array(
                        'nome' => 'peso',
                        'value' => round($dados['peso'],2),
                        'label' => 'Peso (kg)',
                        'show'  => true
                    ),
                    array(
                        'nome' => 'imc',
                        'value' => round($dados['resultado'],2),
                        'label' => 'IMC',
                        'show'  => true
                    ),

                )
            );



            // $historico['imc'][]  = array(
            //     'timestamp' => ($timestamp - 10800)*1000,
            //     'valor'=>$dados['resultado'],
            //     'tick_imc' => 1,
            //     'titulo_imc' =>'Índice de Massa Corporal',
            //     'sufixo_imc' => ''
            // );

            // $historico['altura'][]  = array(
            //     'timestamp' => ($timestamp - 10800)*1000,
            //     'valor'=>$dados['altura'],
            //     'tick_imc' => 5,
            //     'titulo_imc' =>'Altura',
            //     'sufixo_imc' => 'cm'
            // );

            // $historico['peso'][]  = array(
            //     'timestamp' => ($timestamp - 10800)*1000,
            //     'valor'=>$dados['peso'],
            //     'tick_imc' => 2,
            //     'titulo_imc' =>'Peso',
            //     'sufixo_imc' => 'kg'
            // );

        }

        return $historico;

    }//fim getHistoricoImc

     /**
     * [getResultadoImc description]
     *
     * calcula a cor que irá utilizar para atualizacao
     *
     * @param  decimal $resultado [description]
     * @return [type]             [description]
     */
    public function getResultadoImc($resultado)
    {

        $codigo_cor = 4; //vermelho
        $percentual = 0;

        if(!empty($resultado)){
            if($resultado < 18.5){
                $codigo_cor = 2; //amarelo
                $texto = 'Baixo peso';
                $percentual = 50; // valores fixados por solicitacao - dev. app.
            } else if ($resultado >= 18.5 && $resultado < 25){
                $codigo_cor = 1; //verde
                $texto = 'Peso normal';
                $percentual = 15;
            } else if ($resultado >= 25 && $resultado < 30){
                $codigo_cor = 3; //laranja
                $texto = 'Sobrepeso';
                $percentual = 50;
            } else if ($resultado >= 30){
                $codigo_cor = 4; //vermelho
                $texto = 'Obesidade (grau I)';
                $percentual = 90;
            }
        }//fim if resultado

        $return['codigo_cor'] = $codigo_cor;
        $return['texto'] = $texto;
        $return['percentual'] = $percentual;

        return $return;
    }//fim getResultadoImc

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
                'altura'=>"altura",
                'peso'=>"peso",
                'resultado'=>"resultado",
                'data_inclusao' => "data_inclusao",
            );
        //pega os dados da consulta
        $dados_grafico = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $grafico = array();

        foreach ($dados_grafico as $dados){

            $grafico[]  = array(
                'name' => "imc",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['resultado'],
            );

        }//fim foreach

        //retorna o objeto
        return $grafico;


    } //fim montaLabelsGrafico
}
