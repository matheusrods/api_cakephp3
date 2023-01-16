<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use App\Utils\DatetimeUtil;


/**
 * UsuariosColesterol Model
 *
 * @method \App\Model\Entity\UsuariosColesterol get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosColesterol newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosColesterol[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosColesterol|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosColesterol saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosColesterol patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosColesterol[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosColesterol findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosColesterolTable extends AppTable
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

        $this->setTable('usuarios_colesterol');
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
            ->integer('total')
            ->allowEmptyString('total');

        $validator
            ->integer('hdl')
            ->allowEmptyString('hdl');

        $validator
            ->integer('ldl')
            ->allowEmptyString('ldl');

        $validator
            ->integer('triglicerideos')
            ->allowEmptyString('triglicerideos');

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
     * [getHistoricoColesterol description]
     * 
     * metodo para pegar o historico do colesterol
     * 
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    public function getHistoricoColesterol($codigo_usuario)
    {
        //metodo para filtrar os dados do codigo usuario
        $conditions = array('codigo_usuario' => $codigo_usuario);

        //ordena os dados do usuario
        $order = 'data_inclusao desc';
        $limit = '10';

        $fields = array(
            'codigo' => 'codigo',
            'total' => "total",
            'hdl' => "hdl",
            'ldl'=>"ldl",
            'trigli'=>"triglicerideos",
            'resultado' => 'classificacao',
            'data_inclusao'=>"data_inclusao",
            'ANO'=>"YEAR(data_inclusao)",
            'MES'=>"REPLICATE('0',2 - LEN(CAST(MONTH(data_inclusao) AS VARCHAR(2)))) + CAST(MONTH(data_inclusao) AS VARCHAR(2))",
            'DIA'=>"REPLICATE('0',2 - LEN(CAST(DAY(data_inclusao) AS VARCHAR(2)))) + CAST(DAY(data_inclusao) AS VARCHAR(2))",
            'HORAS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(HOUR,data_inclusao) AS VARCHAR(2))",
            'MINUTOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(MINUTE,data_inclusao) AS VARCHAR(2))",
            'SEGUNDOS'=>"REPLICATE('0',2 - LEN(CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2)))) + CAST(DATEPART(SECOND,data_inclusao) AS VARCHAR(2))"
        );

        //pega os dados da consulta
        $dados_colesterol = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        $grafico_total = array();
        
        $dt = new DatetimeUtil();

        foreach ($dados_colesterol as $dados){            
            $timestamp = mktime($dados['HORAS'],$dados['MINUTOS'],$dados['SEGUNDOS'],$dados['MES'],$dados['DIA'],$dados['ANO']);
            $timestamp = ($timestamp - 10800)*1000;
            
            $resultadoDados = $this->getResultadoColesterol($dados['total']);
            $codigo_cor = $resultadoDados['codigo_cor'];

            $grafico_total[]  = array(
                'codigo' => $dados['codigo'],
                'timestamp' => $timestamp,

                'data_inclusao' => $dados->data_inclusao,

                'resultado' => array(
                    'valor' => round($dados['total'],2),
                    'label' => $dados['resultado'],
                ),
                'label' => array(
                    //Alguns indicadores exibe um label colorido
                    'codigo_cor' => $codigo_cor,
                    'texto' => $dados['resultado'],
                ),

                'campos' => array(
                    array(
                        'nome' => 'total',
                        'value' => round($dados['total'],2),
                        'label' => 'Total',
                        'show' => true
                    ),
                    array(
                        'nome' => 'hdl',
                        'value' => round($dados['hdl'],2),
                        'label' => 'Hdl',
                        'show' => true
                    ),
                    array(
                        'nome' => 'ldl',
                        'value' => round($dados['ldl'],2),
                        'label' => 'Ldl',
                        'show' => true
                    ),
                    array(
                        'nome' => 'triglicerideos',
                        'value' => round($dados['trigli'],2),
                        'label' => 'Triglicerideos',
                        'show' => true
                    ),

                )
            );



            // $grafico_total[]  = array(
            //                         'timestamp'=>($timestamp - 10800)*1000,
            //                         'data_inclusao' => $dt->convertDate($dados['data_inclusao'],'Y-m-d H:i:s'),
            //                         'valor'=>$dados['total'],
            //                         'hdl'=>$dados['hdl'],
            //                         'ldl' =>$dados['ldl'],
            //                         'trigli' => $dados['trigli']
            //                     );
        }

        // debug($grafico_total);exit;
        return $grafico_total;

    }//fim gethistoricocolesterol

    /**
     * [getResultadoColesterol description]
     * 
     * calcula a cor que irá utilizar para atualizacao
     * 
     * @param  $resultado [description]
     * @return [type]             [description]
     */
    public function getResultadoColesterol($resultado)
    {
        $codigo_cor = 4; //vermelho
        $percentual = 0;

        if(!empty($resultado)){
            if($resultado < 200){
                $codigo_cor = 1; //verde
                $percentual = 15; // valores fixados por solicitacao - dev. app.
            } else if ($resultado >= 200 && $resultado < 239){
                $codigo_cor = 2; //amarelo
                $percentual = 50;
            } else if ($resultado >= 240){
                $codigo_cor = 4; //vermelho
                $percentual = 90;
            }
        }//fim if resultado

        $dados['codigo_cor'] = $codigo_cor;
        $dados['percentual'] = $percentual;

        return $dados;

    }//fim getResultadoColesterol

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
                'total' => "total",
                'hdl' => "hdl",
                'ldl'=>"ldl",
                'trigli'=>"triglicerideos",
                'resultado' => 'classificacao',
                'data_inclusao'=>"data_inclusao",
            );
        //pega os dados da consulta
        $dados_pressao = $this->find()->select($fields)->where($conditions)->order($order)->limit($limit)->all();

        //variavel auxiliar
        $dados_grafico = array();
        $dados_grafico_hdl = array();
        $dados_grafico_ldl = array();
        $dados_grafico_tri = array();
               
        foreach ($dados_pressao as $dados){            
            
            $dados_grafico_tri[] = array(
                'name' => "triglicerideos",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => 'Referência: 57 a 99 mg/dL', //Label que tem a cima do grafico
                'minimo' => '90', // serve para traçar linhas de referencia no grafico.
                'maximo' => '120', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['trigli'],
            );

            $dados_grafico_hdl[] = array(
                'name' => "hdl",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['hdl'],
            );

            $dados_grafico_ldl[] = array(
                'name' => "ldl",
                'codigo' => $dados['codigo'],
                'data_inclusao' => $dados->data_inclusao,
                'referencia' => '', //Label que tem a cima do grafico
                'minimo' => '', // serve para traçar linhas de referencia no grafico.
                'maximo' => '', // serve para traçar linhas de referencia no grafico.
                'value' => $dados['ldl'],
            );
        
        }//fim foreach

        $dados_grafico[] = $dados_grafico_tri;
        $dados_grafico[] = $dados_grafico_ldl;
        $dados_grafico[] = $dados_grafico_hdl;
            
        //retorna o objeto
        return $dados_grafico;


    } //fim montaLabelsGrafico
}
