<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use App\Utils\DatetimeUtil;

/**
 * UsuariosGlicose Model
 *
 * @method \App\Model\Entity\UsuariosGlicose get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosGlicose newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosGlicose[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosGlicose|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosGlicose saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosGlicose patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosGlicose[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosGlicose findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosGlicoseTable extends AppTable
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

        $this->setTable('usuarios_glicose');
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
            ->integer('glicose')
            ->allowEmptyString('glicose');

        $validator
            ->numeric('hemoglobina_glicada')
            ->allowEmptyString('hemoglobina_glicada');

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
     * [getHistoricoGlicose description]
     * 
     * metodo para pegar os dados de historico da glicose
     * 
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getHistoricoGlicose($codigo_usuario)
    {
        //conditions
        $conditions = array('codigo_usuario' => $codigo_usuario);

        $order = 'data_inclusao desc';
        $limit = '10';

        $fields = array(
                'codigo' => 'codigo',
                'glicose'=>"glicose",
                'hemo_glicada'=>"hemoglobina_glicada",
                'data_inclusao'=>"data_inclusao",
                'resultado' => 'classificacao',
                'ANO'=>"YEAR(data_inclusao)",
                'MES'=>"REPLICATE('0',2 - LEN(CAST(MONTH(data_inclusao) AS VARCHAR(2)))) + CAST(MONTH(data_inclusao) AS VARCHAR(2))",
                'DIA'=>"REPLICATE('0',2 - LEN(CAST(DAY(data_inclusao) AS VARCHAR(2)))) + CAST(DAY(data_inclusao) AS VARCHAR(2))",
                'HORAS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2))",
                'MINUTOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2))",
                'SEGUNDOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2))"
            );
        //pega os dados da consulta
        $dados_glicose = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        $grafico_glicose = array();

        foreach ($dados_glicose as $dados){            
            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;

            $resultadoDados = $this->getResultadoGlicose($dados['total']);
            $codigo_cor = $resultadoDados['codigo_cor'];

            $grafico_glicose[]  = array(
                'codigo' => $dados['codigo'],
                'timestamp' => $timestamp,

                'data_inclusao' => $dados->data_inclusao,

                'resultado' => array(
                    'valor' => round($dados['glicose']),
                    'label' => $dados['resultado'],
                ),
                'label' => array(
                    //Alguns indicadores exibe um label colorido
                    'codigo_cor' => $codigo_cor,
                    'texto' => $dados['resultado'],
                ),

                'campos' => array(
                    array(
                        'nome' => 'glicose',
                        'value' => round($dados['glicose'],2),
                        'label' => 'Glicose',
                        'show' => true
                    ),
                    array(
                        'nome' => 'hemoglobina_glicada',
                        'value' => round($dados['hemoglobina_glicada'],2),
                        'label' => 'Hemoglobina glicada',
                        'show' => false
                    ),
                )
            );


            // $grafico_glicose[] = array(
            //                         'timestamp' => ($timestamp - 10800)*1000,
            //                         'data_inclusao' => $dt->convertDate($dados['data_inclusao'],'Y-m-d H:i:s'),
            //                         'valor' => $dados['glicose']
            //                     );
        }
        
        return $grafico_glicose;

    }//fim getHistoricoGlicose

     /**
     * [getResultadoGlicose description]
     * 
     * calcula a cor que irá utilizar para atualizacao
     * 
     * @param  $resultado [description]
     * @return [type]             [description]
     */
    public function getResultadoGlicose($resultado)
    {
        $codigo_cor = 4; //vermelho
        $percentual = 0;

        if(!empty($resultado)){
            if($resultado < 100){
                $codigo_cor = 1; //verde          
                $percentual = 15; // valores fixados por solicitacao - dev. app.
            } else if ($resultado >= 100 && $resultado <= 125){
                $codigo_cor = 2; //amarelo                
                $percentual = 50;
            } else if ($resultado > 125){
                $codigo_cor = 4; //vermelho
                $percentual = 90;
            }
        }//fim if resultado

        $dados['codigo_cor'] = $codigo_cor;
        $dados['percentual'] = $percentual;

        return $dados;

    }//fim getResultadoGlicose

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
                'glicose'=>"glicose",
                'hemo_glicada'=>"hemoglobina_glicada",
                'data_inclusao'=>"data_inclusao",
            );
        //pega os dados da consulta
        $dados_pressao = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $grafico_glicose = array();
               
        foreach ($dados_pressao as $dados){
            
            $grafico_glicose[]  = array(
                'name' => "glicose",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => 'Referência: 57 a 99 mg/dL', //Label que tem a cima do grafico
                'minimo' => '75', // serve para traçar linhas de referencia no grafico.
                'maximo' => '99', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['glicose'],
            );
        
        }//fim foreach

        //retorna o objeto
        return $grafico_glicose;


    } //fim montaLabelsGrafico

}
