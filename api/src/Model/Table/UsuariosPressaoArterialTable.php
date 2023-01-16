<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use App\Utils\DatetimeUtil;

/**
 * UsuariosPressaoArterial Model
 *
 * @method \App\Model\Entity\UsuariosPressaoArterial get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosPressaoArterial findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosPressaoArterialTable extends AppTable
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

        $this->setTable('usuarios_pressao_arterial');
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
            ->integer('pressao_arterial_diastolica')
            ->allowEmptyString('pressao_arterial_diastolica');

        $validator
            ->integer('pressao_arterial_sistolica')
            ->allowEmptyString('pressao_arterial_sistolica');

        $validator
            ->scalar('classificacao')
            ->maxLength('classificacao', 100)
            ->allowEmptyString('classificacao');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    /**
     * [getHistoricoPressaoArterial description]
     * 
     * metodo para pegar os dados de historico
     * 
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getHistoricoPressaoArterial($codigo_usuario)
    {
        //monta os filtros
        $conditions = array('codigo_usuario' => $codigo_usuario);

        $order = 'data_inclusao desc';
        $limit = '10';

        //campos da tabela
        $fields = array(
                'codigo',
                'pad'=>"pressao_arterial_diastolica",
                'pas'=>"pressao_arterial_sistolica",
                'classificacao',
                'data_inclusao'=>"data_inclusao",
                'ANO'=>"YEAR(data_inclusao)",
                'MES'=>"REPLICATE('0',2 - LEN(CAST(MONTH(data_inclusao) AS VARCHAR(2)))) + CAST(MONTH(data_inclusao) AS VARCHAR(2))",
                'DIA'=>"REPLICATE('0',2 - LEN(CAST(DAY(data_inclusao) AS VARCHAR(2)))) + CAST(DAY(data_inclusao) AS VARCHAR(2))",
                'HORAS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2))",
                'MINUTOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2))",
                'SEGUNDOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2))"
            );
        //pega os dados da consulta
        $dados_pressao = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $dados_grafico = array();

        foreach ($dados_pressao as $dados){            
            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;

            $resultadoPressao = $this->getResultadoPressao($dados['pas'],$dados['pad']);
            $codigo_cor = $resultadoPressao['codigo_cor'];
            $dadoResultado = $resultadoPressao['result'];

            $dados_grafico[] = array(
                'codigo' => $dados['codigo'],
                'timestamp' => $timestamp,

                'data_inclusao' => $dados->data_inclusao,

                'resultado' => array(
                    'valor' => round($dadoResultado,2),
                    'label' => $dados['classificacao'],
                ),
                'label' => array(
                    //Alguns indicadores exibe um label colorido
                    'codigo_cor' => $codigo_cor,
                    'texto' => $dados['classificacao'],
                ),

                'campos' => array(
                    array(
                        'nome' => 'pressao_arterial_sistolica',
                        'value' => round($dados['pas'],2),
                        'label' => 'Pressão Arterial Sistólica',
                        'show'=> true
                    ),
                    array(
                        'nome' => 'pressao_arterial_diastolica',
                        'value' => round($dados['pad'],2),
                        'label' => 'Pressão Arterial Diastólica',
                        'show'=> true
                    ),
                )
            );

            // $grafico_pad[] = array(
            //                     'timestamp'=>($timestamp - 10800)*1000,
            //                     'data_inclusao' => $dados->data_inclusao->format('Y-m-d H:i:s'),
            //                     'valor'=>$dados['pad']
            //                 );
            // $grafico_pas[] = array(
            //                     'timestamp'=>($timestamp - 10800)*1000,
            //                     'data_inclusao' => $dados->data_inclusao->format('Y-m-d H:i:s'),
            //                     'valor'=>$dados['pas']
            //                 );
        
        }//fim foreach

        // $dados_grafico['grafico_pad'] = $grafico_pad;
        // $dados_grafico['grafico_pas'] = $grafico_pas;
        
        return $dados_grafico;

    }//fim getHistoricoPressaoArterial

    /**
     * [getResultadoPressao description]
     * 
     * calcula a cor que irá utilizar para atualizacao
     * 
     * @param  $resultado [description]
     * @return [type]             [description]
     */
    public function getResultadoPressao($pas,$pad)
    {

        $resultado = $this->retornaPressao($pas, $pad);

        $codigo_cor = 4; //vermelho
        $percentual = 0;

        if(!empty($resultado)){
            if ($resultado == 10) {
                $codigo_cor = 1; //verde
                $percentual = 15;
            } else if ($resultado == 20) {
                $codigo_cor = 1; //verde
                $percentual = 15;  // valores fixados por solicitacao - dev. app.
            } else if ($resultado == 30) {
                $codigo_cor = 2; //amarelo
                $percentual = 50;
            } else if ($resultado == 40) {
                $codigo_cor = 3; //laranja
                $percentual = 50;
            } else if ($resultado == 50) {
                $codigo_cor = 4; //vermelho
                $percentual = 90;
            }

        }//fim if resultado

        $dados['codigo_cor'] = $codigo_cor;
        $dados['result'] = $resultado;
        $dados['percentual'] = $percentual;

        return $dados;

    }//fim getResultadoPressao

    public function retornaPressao($pas,$pad){
        $retornoPad = 0;
        $retornoPas = 0;

        if ($pad < 85) {
            $retornoPad = 10;
        } else if ($pad >= 85 && $pad < 90) {
            $retornoPad = 20;
        } else if ($pad >= 90 && $pad < 100) {
            $retornoPad = 30;
        } else if ($pad >= 100 && $pad < 110) {
            $retornoPad = 40;
        } else if ($pad >= 110) {
            $retornoPad = 50;
        } else {
            $retornoPad = 0;
        }

        if ($pas < 130) {
            $retornoPas = 10;
        } else if ($pas >= 130 && $pas < 140) {
            $retornoPas = 20;
        } else if ($pas >= 140 && $pas < 160) {
            $retornoPas = 30;
        } else if ($pas >= 160 && $pas < 180) {
            $retornoPas = 40;
        } else if ($pas >= 180) {
            $retornoPas = 50;
        } else {
            $retornoPas = 0;
        }

        $retornoFinal = ($retornoPas >= $retornoPad ? $retornoPas : $retornoPad);
        return $retornoFinal;
    }

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
                'codigo',
                'pad'=>"pressao_arterial_diastolica",
                'pas'=>"pressao_arterial_sistolica",
                'classificacao',
                'data_inclusao'=>"data_inclusao"
            );
        //pega os dados da consulta
        $dados_pressao = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $dados_grafico = array();
               
        foreach ($dados_pressao as $dados){            
            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;

            $resultadoPressao = $this->getResultadoPressao($dados['pas'],$dados['pad']);
            $codigo_cor = $resultadoPressao['codigo_cor'];
            $dadoResultado = $resultadoPressao['result'];            

            $dados_grafico_pas[] = array(
                'name' => "pas",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['pas'],
                'label' => 'Pressão Arterial Sistólica',
            );


            $dados_grafico_pad[] = array(
                'name' => "pad",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.                
                'value' => $dados['pad'],
                'label' => 'Pressão Arterial Diastólica',
            );

            // $grafico_pad[] = array(
            //                     'timestamp'=>($timestamp - 10800)*1000,
            //                     'data_inclusao' => $dados->data_inclusao->format('Y-m-d H:i:s'),
            //                     'valor'=>$dados['pad']
            //                 );
            // $grafico_pas[] = array(
            //                     'timestamp'=>($timestamp - 10800)*1000,
            //                     'data_inclusao' => $dados->data_inclusao->format('Y-m-d H:i:s'),
            //                     'valor'=>$dados['pas']
            //                 );
        
        }//fim foreach

        $dados_grafico[] = $dados_grafico_pas;
        $dados_grafico[] = $dados_grafico_pad;
            
        //retorna o objeto
        return $dados_grafico;


    } //fim montaLabelsGrafico

}
