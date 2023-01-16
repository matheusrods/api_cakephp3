<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;

use Cake\Http\Client;
use App\Utils\DatetimeUtil;
use App\Utils\ConvertUtil;
/**
 * Indicadores Controller
 *
 * @property \App\Model\Table\UsuariosPressaoArterialTable $UsuariosPressaoArterial
 * @property \App\Model\Table\UsuariosColesterolTable $UsuariosColesterol
 * @property \App\Model\Table\UsuariosGlicoseTable $UsuariosGlicose
 * @property \App\Model\Table\UsuariosAbdominalTable $UsuariosAbdominal
 * @property \App\Model\Table\UsuariosImcTable $UsuariosImc
 *
 */
class IndicadoresController extends ApiController
{
    var $uses = false;

    /**
     * View method
     *
     */
    public function getInfos($codigo_usuario)
    {
        $data = array();

        //pega os dados atuais de saude
        $data = $this->info_saude($codigo_usuario);

        $this->set(compact('data'));

    }//fim view


    private function graficoAjustarArray(array $arrayGraficos){
        
        $arrNovo = [];
        foreach ($arrayGraficos as $key => $value) {

            $arrNovo['name'] = $value['name'];
            $arrNovo['minimo'] = $value['minimo'];
            $arrNovo['maximo'] = $value['maximo'];
            $arrNovo['referencia'] = $value['referencia'];

            // if($value['name'] == 'pressao_arterial'){

            //     if(isset($value['resultado']) && is_array($value['resultado'])){

            //         foreach ($value['resultado'] as $keyr => $valuer) {
            //                 $_nome = $valuer['nome'];
            //                 $_value = $valuer['value'];
            //                 $_label = $valuer['label'];

            //                 $arrNovo['resultado'][] = [
            //                     'nome'=>$_nome,
            //                     'valor'=>$_value,
            //                     'label'=>$_label
            //                 ];
            //         }
            //     }

            // }
            //  else {
                $data_inclusao = \DateTime::createFromFormat('Y-m-d H:i:s', $value['data_inclusao'])->format('Y-m-d');
                 $arrNovo['resultado'][] = [
                     'codigo' => $value['codigo'],
                     'data'=> $data_inclusao,
                     'valor'=> round($value['value'],2)
                 ];
            // }
        }

        return $arrNovo;
    }
    /**
     *
     * @param [type] $arrayGraficos
     * @return void
     */
    private function graficoAjustarResposta(array $arrayGraficos){

        $val = [];
        if(isset($arrayGraficos[0][0]['name']) && ($arrayGraficos[0][0]['name'] == 'triglicerideos' || $arrayGraficos[0][0]['name'] == 'pas')) // <-- TODO
        {
            foreach ($arrayGraficos as $key => $value) {
                $val[] = $this->graficoAjustarArray($value);
            }

        } else {
            $val[] = $this->graficoAjustarArray($arrayGraficos);
        }

        return $val;

    }

    /**
     * [info_saude description]
     *
     * metodo para pegar os dados dos indicadores atuais
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function info_saude($codigo_usuario)
    {
        //instancia as tabelas de indicadores
        $this->loadModel('UsuariosPressaoArterial');
        $this->loadModel('UsuariosColesterol');
        $this->loadModel('UsuariosGlicose');
        $this->loadModel('UsuariosAbdominal');
        $this->loadModel('UsuariosImc');
        $this->loadModel('UsuariosDados');

        //filtra pelo codigo usuario
        $conditions = array('codigo_usuario' => $codigo_usuario);
        //ordena a consulta
        $order = 'data_inclusao desc';

        //pega os dados do usuario para utilizacao pontuais
        $usuarios_dados = $this->UsuariosDados->find()->where($conditions)->first();

        //pega os dados
        ############################PRESSAO
        $info_pressao    = $this->UsuariosPressaoArterial->find()->where($conditions)->order($order)->first();

        //verifica se tem dados
        if(!empty($info_pressao)) {
            $info_pressao['resultado'] = $info_pressao['classificacao'];

            $resultadoPressao = $this->UsuariosPressaoArterial->getResultadoPressao($info_pressao['pressao_arterial_sistolica'],$info_pressao['pressao_arterial_diastolica']);
            $info_pressao['codigo_cor'] = $resultadoPressao['codigo_cor'];
            $info_pressao['percentual'] = $resultadoPressao['percentual'];
        }

        ###########################COLESTEROL
        $info_colesterol = $this->UsuariosColesterol->find()->where($conditions)->order($order)->first();

        if(!empty($info_colesterol)) {
            $info_colesterol['resultado'] = $info_colesterol['classificacao'];

            $resultadoColesterol = $this->UsuariosColesterol->getResultadoColesterol($info_colesterol['total']);
            $info_colesterol['codigo_cor'] = $resultadoColesterol['codigo_cor'];
            $info_colesterol['percentual'] = $resultadoColesterol['percentual'];
        }

        ##########################GLICOSE
        $info_glicose    = $this->UsuariosGlicose->find()->where($conditions)->order($order)->first();

        if(!empty($info_glicose)){
            $info_glicose['resultado'] = $info_glicose['classificacao'];

            $resultadoGlicose = $this->UsuariosGlicose->getResultadoGlicose($info_glicose['glicose']);
            $info_glicose['codigo_cor'] = $resultadoGlicose['codigo_cor'];
            $info_glicose['percentual'] = $resultadoGlicose['percentual'];
        }

        #########################ABDOMINAL
        $info_abdominal  = $this->UsuariosAbdominal->find()->where($conditions)->order($order)->first();

        if(!empty($info_abdominal)) {
            $info_abdominal['resultado'] = $info_abdominal['classificacao'];

            $resultadoAbdomen = $this->UsuariosAbdominal->getResultadoAbdominal($info_abdominal['circ_media'],$usuarios_dados['sexo']);
            $info_abdominal['codigo_cor'] = $resultadoAbdomen['codigo_cor'];
            $info_abdominal['resultado'] = $resultadoAbdomen['texto'];
            $info_abdominal['percentual'] = $resultadoAbdomen['percentual'];
            $info_abdominal['circ_media'] = round($info_abdominal['circ_media'],2);
        }

        #########################IMC
        $info_imc        = $this->UsuariosImc->find()->where($conditions)->order($order)->first();

        if(!empty($info_imc)) {
            $retorno_imc = $this->UsuariosImc->getResultadoImc($info_imc['resultado']);
            $info_imc['resultado'] = $retorno_imc['texto'];
            $info_imc['codigo_cor'] = $retorno_imc['codigo_cor'];
            $info_imc['percentual'] = $retorno_imc['percentual'];
        }

        $dados['pressao_arterial'] = $info_pressao;
        $dados['pressao_arterial']['formulario'] = $this->montaFormulario(1);
        if(!empty($info_pressao)){
            $dados['pressao_arterial']['historico'] = $this->historicoPressaoArterial($codigo_usuario);
            $dados['pressao_arterial']['grafico'] = $this->graficoAjustarResposta( (array)$this->UsuariosPressaoArterial->montaLabelsGrafico($codigo_usuario));
        }

        $dados['colesterol'] = $info_colesterol;
        $dados['colesterol']['formulario'] = $this->montaFormulario(2);
        if(!empty($info_colesterol)){
            $dados['colesterol']['historico'] = $this->historicoColesterol($codigo_usuario);
            $dados['colesterol']['grafico'] = $this->graficoAjustarResposta( (array)$this->UsuariosColesterol->montaLabelsGrafico($codigo_usuario));
        }

        $dados['glicose'] = $info_glicose;
        $dados['glicose']['formulario'] = $this->montaFormulario(3);
        if(!empty($info_glicose)){
            $dados['glicose']['historico'] = $this->historicoGlicose($codigo_usuario);
            $dados['glicose']['grafico'] = $this->graficoAjustarResposta( (array)$this->UsuariosGlicose->montaLabelsGrafico($codigo_usuario) );
        }

        $dados['abdominal'] = $info_abdominal;
        $dados['abdominal']['formulario'] = $this->montaFormulario(4);
        if(!empty($info_abdominal)) {
            $dados['abdominal']['historico'] = $this->historicoCircuferenciaAbdominal($codigo_usuario,$usuarios_dados['sexo']);
            $dados['abdominal']['grafico'] = $this->graficoAjustarResposta( (array)$this->UsuariosAbdominal->montaLabelsGrafico($codigo_usuario,$usuarios_dados['sexo']));
        }

        $dados['imc'] = $info_imc;
        $dados['imc']['formulario'] = $this->montaFormulario(5);
        if(!empty($info_imc)) {
            $dados['imc']['historico'] = $this->historicoImc($codigo_usuario);
            $dados['imc']['grafico'] = $this->graficoAjustarResposta( (array)$this->UsuariosImc->montaLabelsGrafico($codigo_usuario));
        }

        return $dados;

    }//fim info_saude

    /**
     * [historico_pressao_arterial description]
     *
     * metodo para pegar os dados de historico da pressao_arterial
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function historicoPressaoArterial($codigo_usuario)
    {
        //metodo para indicadores
        $dados = $this->UsuariosPressaoArterial->getHistoricoPressaoArterial($codigo_usuario);

        return $dados;

    }//pega os dados historicos da pressao arterial

    /**
     * [historico_colesterol description]
     *
     * metodo para pegar os dados de historico do colesterol
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function historicoColesterol($codigo_usuario)
    {
        //metodo para indicadores
        $dados = $this->UsuariosColesterol->getHistoricoColesterol($codigo_usuario);

        return $dados;
    }

    /**
     * [historicoGlicose description]
     *
     * metodo para pegar os dados historicos
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function historicoGlicose($codigo_usuario)
    {

        //metodo para indicadores
        $dados = $this->UsuariosGlicose->getHistoricoGlicose($codigo_usuario);

        return $dados;
    }//fim historicoGlicose

    /**
     * [historicoCircuferenciaAbdominal description]
     *
     * metodo para pegar os dados historico da circunferencia
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function historicoCircuferenciaAbdominal($codigo_usuario,$sexo)
    {

        //metodo para indicadores
        $dados = $this->UsuariosAbdominal->getHistoricoAbdominal($codigo_usuario,$sexo);

        return $dados;
    }

    /**
     * [historicoImc description]
     *
     * metodo para pegar os dados historicos do imc do usuario
     *
     * @param  [type] $codigo_usuario [description]
     * @return [type]                 [description]
     */
    private function historicoImc($codigo_usuario)
    {
        //metodo para indicadores
        $dados = $this->UsuariosImc->getHistoricoImc($codigo_usuario);

        return $dados;

    }//fim historicoImc

    /**
     * [montaFormulario description]
     *
     * monta o objeto do formulario
     *
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function montaFormulario($type)
    {
        //objeto base
        $dados = array();

        //verifica qual o tipo de objeto vai montar
        switch ($type) {
            case '1': //pressao arterial
                //dados de retorno
                $dados = array(
                    'titulo' => "Pressão Arterial",
                    'descricao' => "Texto para inserir de pressão arterial",
                    'campos' => array(
                        array(
                            'name' => 'pressao_arterial_sistolica',
                            'label' => 'Pressão arterial sistólica (mmHg)',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'pressao_arterial_diastolica',
                            'label' => 'Pressão arterial diastólica (mmHg)',
                            'type' => 'int',
                            'maxlength' => '3'
                        )
                    )
                );
                break;
            case '2': //colesterol
                //dados de retorno
                $dados = array(
                    'titulo' => "Colesterol",
                    'descricao' => "Texto para inserir de colesterol",
                    'campos' => array(
                        array(
                            'name' => 'total',
                            'label' => 'Total',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'ldl',
                            'label' => 'Ldl',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'hdl',
                            'label' => 'Hdl',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'triglicerideos',
                            'label' => 'Triglicerídeos',
                            'type' => 'int',
                            'maxlength' => '3'
                        )
                    )
                );
                break;
            case '3': //glicose
                //dados de retorno
                $dados = array(
                    'titulo' => "Glicose",
                    'descricao' => "Texto para inserir de glicose",
                    'campos' => array(
                        array(
                            'name' => 'glicose',
                            'label' => 'Glicose',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'hemoglobina_glicada',
                            'label' => 'Hemoglobina glicada',
                            'type' => 'int',
                            'maxlength' => '6'
                        )
                    )
                );
                break;
            case '4': //abdominal
                //dados de retorno
                $dados = array(
                    'titulo' => "Circunferência abdominal",
                    'descricao' => "Texto para inserir de circunferencia abdominal de medidas",
                    'campos' => array(
                        array(
                            'name' => 'circ_abdom',
                            'label' => ' Circunferência abdominal',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'circ_quadril',
                            'label' => 'Circunferência quadril',
                            'type' => 'int',
                            'maxlength' => '3'
                        )
                    )
                );
                break;
            case '5': //imc
                //dados de retorno
                $dados = array(
                    'titulo' => "IMC",
                    'descricao' => "Texto para inserir de imc",
                    'campos' => array(
                        array(
                            'name' => 'altura',
                            'label' => 'Altura (cm)',
                            'type' => 'int',
                            'maxlength' => '3'
                        ),
                        array(
                            'name' => 'peso',
                            'label' => 'Peso (kg)',
                            'type' => 'decimal',
                            'maxlength' => '6'
                        )
                    )
                );
                break;
        }//fim switch

        //retorna o objeto
        return $dados;


    } //fim montaFormulario


    /**
     * [montaLabelsGrafico description]
     *
     * monta o objeto de labels grafico
     *
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function montaLabelsGrafico($type)
    {
        //objeto base
        $dados = array();

        //verifica qual o tipo de objeto vai montar
        switch ($type) {
            case '1': //pressao arterial
                //dados de retorno
                $dados = array(
                    'name' => "pressao_arterial",
                );
                break;
            case '2': //colesterol
                //dados de retorno
                $dados = array(
                    'name' => 'triglicerideos',
                    'referencia' => 'Referência: 57 a 99 mg/dL', //Label que tem a cima do grafico
                    'minimo' => 90, // serve para traçar linhas de referencia no grafico.
                    'maximo' => 120, // serve para traçar linhas de referencia no grafico.
                );
                break;
            case '3': //glicose
                //dados de retorno
                $dados = array(
                    'name' => "glicose",
                );
                break;
            case '4': //abdominal
                //dados de retorno
                $dados = array(
                    'name' => "abdominal",
                );
                break;
            case '5': //imc
                //dados de retorno
                $dados = array(
                    'nome' => "IMC",
                );
                break;
        }//fim switch

        //retorna o objeto
        return $dados;


    } //fim montaLabelsGrafico


    /**
     * [setInfos description]
     *
     * seta as informações: IMC, Glicose, Pressão Arterial, Abdomen, Colesterol, Tabagismo
     *
     * @param int $codigo_usuario [description]
     */
    public function setInfos($codigo_usuario)
    {
        //seta a variavel para retorno da mensagem
        $data = "Dados salvo com sucesso!";
        $error = array();

        //verifica se é post
        if ($this->request->is(['post','put'])) {

            //instancia as tabelas de indicadores
            $this->loadModel('UsuariosPressaoArterial');
            $this->loadModel('UsuariosColesterol');
            $this->loadModel('UsuariosGlicose');
            $this->loadModel('UsuariosAbdominal');
            $this->loadModel('UsuariosImc');
            $this->loadModel('IndicadoresImagens');
            $this->loadModel('UsuariosDados');



            //pega os dados enviados
            $dados = $this->request->getData();

            //para edicao
            $codigo_indicador = null;
            if(isset($dados['codigo'])) {
                $codigo_indicador = (!empty($dados['codigo'])) ? $dados['codigo'] : null;
            }//fim codigo
            
            //verifica se existe o indice id
            if(isset($dados['id'])) {
                //pega os valores
                $val = $dados['valor'];
                
                //verifica qual é o tipo que está inserindo
                switch ($dados['id']) {
                    case 'imc':
                        //seta as variaveis passadas
                        $altura_em_mm = $val['altura'] / 100;
                        $peso = str_replace(",", ".", $val['peso']);

                        //calcula o resultado
                        $resultado = round($peso / ($altura_em_mm * $altura_em_mm),2);

                        //monta os dados para gravar
                        $dados_imc['altura'] = $val['altura'];
                        $dados_imc['peso'] = $val['peso'];
                        $dados_imc['codigo_usuario'] = $codigo_usuario;
                        $dados_imc['resultado'] = $resultado;

                        //verifica se existe dados para edicao
                        $imc = $this->UsuariosImc->find()->where(['codigo' => $codigo_indicador])->first();
                        //veificacao para edicao
                        if(empty($imc)) {
                            $dados_imc['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta novo imc
                            $imc = $this->UsuariosImc->newEntity($dados_imc);
                        }
                        else {
                            $imc = $this->UsuariosImc->patchEntity($imc, $dados_imc);
                        }

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosImc->save($imc)) {
                            // debug($imc->errors());
                            $error[] = "Erro ao inserir IMC";
                        }//fim save
                        else {

                            $dados = $this->UsuariosImc->getResultadoImc($resultado);
                            $sexo = $this->UsuariosDados->getSexoUsuario($codigo_usuario);
                            $indicadores_imagens = $this->IndicadoresImagens->getIndicadoresImagens($resultado, $sexo);

                            if(is_null($indicadores_imagens)) {
                                $indicadores_imagens['imagem'] = '/nina/2019/12/05/A2BDCE9C-1A80-35AF-98B6-955217730731.png';
                            }

                            //dados de retorno
                            $data = array(
                                'label_resultado' => 'IMC',
                                'resultado' => $resultado,
                                'imagem' => FILE_SERVER . $indicadores_imagens['imagem'],
                                'texto' => 'O seu IMC: ' . $resultado . ' - ' . $dados['texto'],
                                'legenda' => 'O IMC de 18,5 a 24,9 é considerado normal.',
                                'entenda' => [
                                    'titulo' => 'IMC',
                                    'conteudo' => [
                                        ['subtitulo' => '',
                                        'subconteudo' => (new ConvertUtil)->stringToBase64('O índice de massa corporal é um índice estatístico utilizado para classificar a população dividindo o peso atual pelo quadrado da altura em centímetros, de acordo com a fórmula abaixo:

IMC = Peso (kg)/Altura2 (cm)

É utilizado globalmente pela Organização Mundial da Saúde (OMS), para classificação de acordo com a categoria (baixo peso, peso ideal e excesso de peso/obesidade).

Assim, os **adultos com idade superior a 20 anos e inferior a 65 anos**, devem comparar o valor obtido com a seguinte tabela de índice de massa corporal:

|IMC (Kg/m2)        |                     |
|-------------------|---------------------|
|Magreza extrema	|		< 16.5        |
|Baixo peso	    	|	 	< 18.5        |
|Normoponderal		|	  18.5 – 24.9     |
|Excesso de peso	|	    25 – 29.9     |
|Obesidade, grau I	|	   30 – 34.9      |
|Obesidade, grau II	|     	35 – 39.9     |
|Obesidade mórbida	|       ≥ 40          |
|Excesso de peso*	|		23-24.9       |
|Obesidade*		    |		≥ 25          |
                                            
                                        ')],
                                    ]
                                ]

                            );
                        }

                        break;
                    case 'pressao_arterial':
                        //dados para gravar
                        $pas = $val['pressao_arterial_sistolica'];
                        $pad = $val['pressao_arterial_diastolica'];

                        $dadoPressao =  $this->UsuariosPressaoArterial->retornaPressao($pas,$pad);
                        $resultado = '';
                        if ($dadoPressao == 10) {
                            $resultado = 'Normal';
                        } else if ($dadoPressao == 20) {
                            $resultado = 'Normal limítrofe';
                        } else if ($dadoPressao == 30) {
                            $resultado = 'Hipertensão leve';
                        } else if ($dadoPressao == 40) {
                            $resultado = 'Hipertensão moderada';
                        } else if ($dadoPressao == 50) {
                            $resultado = 'Hipertensão grave';
                        }

                        //monta o array para insercao
                        $dado['pressao_arterial_diastolica'] = $pad;
                        $dado['pressao_arterial_sistolica'] = $pas;
                        $dado['classificacao'] = $resultado;
                        $dado['codigo_usuario'] = $codigo_usuario;

                        //verifica se existe dados para edicao
                        $res = $this->UsuariosPressaoArterial->find()->where(['codigo' => $codigo_indicador])->first();
                        //veificacao para edicao
                        if(empty($res)) {
                            $dado['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta novo medidas
                            $res = $this->UsuariosPressaoArterial->newEntity($dado);
                        }
                        else {
                            $res = $this->UsuariosPressaoArterial->patchEntity($res, $dado);
                        }

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosPressaoArterial->save($res)) {
                            $error[] = "Erro ao inserir Pressão Arterial";
                        }//fim save
                        else {

                            //dados de retorno
                            $data = array(
                                'label_resultado' => 'Pressão Arterial',
                                'resultado' => $resultado,
                                'texto' => 'A sua pressão é: ' . $resultado,
                                'imagem' => '',
                                'legenda' => '',
                                'entenda' => [
                                    'titulo' => 'Pressão Arterial',
                                    'conteudo' => [
                                        ['subtitulo' => '',
                                        'subconteudo' => (new ConvertUtil)->stringToBase64('A medida da pressão arterial, pela sua importância, deve ser estimulada e realizada, em toda avaliação de saúde, por médicos de todas as especialidades e demais profissionais da área de saúde.

Segue abaixo, um quadro com os valores de referência:

| PAD    |    PAS    |  Classificação(mmHg)(mmHg)                        |
|--------|-----------|---------------------------------------------------|
|< 85   |   < 130   |  Normal                                            |
|85-89  |   130-139 |  Normal limítrofe                                  |
|90-99  |   140-159 |  Hipertensão leve (estágio 1)                      |
|100-109|   160-179 |  Hipertensão moderada (estágio 2)                  |
|> 110  |   > 180   |  Hipertensão grave (estágio 3)                     |
|< 90   |   > 140   |  Hipertensão sistólica isolada                     |

                                        ')
                                        ],
                                    ]
                                ]
                            );
                        }

                        break;
                    case 'colesterol':
                        //dados para gravar
                        $hdl = $val['hdl'];
                        $ldl = $val['ldl'];
                        $total = $val['total'];
                        $triglicerideos = $val['triglicerideos'];

                        if ($total < 200) {
                            $resultado = 'Desejável';
                        } else if ($total >= 200 && $total <= 239) {
                            $resultado = 'Limítrofe';
                        } else if ($total >= 240) {
                            $resultado = 'Elevado';
                        }

                        //monta o array para insercao
                        $dado['hdl'] = $hdl;
                        $dado['ldl'] = $ldl;
                        $dado['triglicerideos'] = $triglicerideos;
                        $dado['total'] = $total;
                        $dado['classificacao'] = $resultado;
                        $dado['codigo_usuario'] = $codigo_usuario;

                        //verifica se existe dados para edicao
                        $res = $this->UsuariosColesterol->find()->where(['codigo' => $codigo_indicador])->first();
                        //veificacao para edicao
                        if(empty($res)) {
                            $dado['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta novo medidas
                            $res = $this->UsuariosColesterol->newEntity($dado);
                        }
                        else {
                            $res = $this->UsuariosColesterol->patchEntity($res, $dado);
                        }

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosColesterol->save($res)) {
                            $error[] = "Erro ao inserir Colesterol";
                        }//fim save
                        else {

                            //dados de retorno
                            $data = array(
                                'label_resultado' => 'Colesterol',
                                'resultado' => $resultado,
                                'texto' => 'O resultado é: ' . $total . " - " . $resultado,
                                'imagem' => '',
                                'legenda' => '',
                                'entenda' => [
                                    'titulo' => 'Colesterol',
                                    'conteudo' => [
                                        ['subtitulo' => '',
                                         'subconteudo' => (new ConvertUtil)->stringToBase64('O colesterol total deve ser sempre abaixo de 190 mg/dL. Ter o colesterol total alto nem sempre significa que a pessoa está doente, pois pode ocorrer por um aumento colesterol bom (HDL), o que também faz subir os valores do colesterol total. Assim, deve-se sempre levar em consideração os valores do colesterol HDL (bom), do colesterol LDL (ruim) e o dos triglicerídios para analisar o risco da pessoa de desenvolver doenças cardiovasculares.

Confira na tabela abaixo os valores de referência desejáveis de colesterol, de acordo com a idade, pela sociedade brasileira de cardiologia:


|Tipo de colesterol         |     Valor de referência                                                  |
|---------------------------|--------------------------------------------------------------------------|
|Colesterol total	         | menor que 190 mg/dl                                                      |
|Colesterol HDL (bom)	     | maior que 40 mg/dl                                                       |
|Colesterol LDL (ruim)	     | menor que  130 mg/dl - em pessoas com risco cardiovascular baixo*        |
|			                 | menor que 100 mg/dl - em pessoas com risco cardiovascular intermediário* |
|			                 | menor que  70 mg/dl - em pessoas com risco cardiovascular alto*          |
|			                 | menor que 50 mg/dl - em pessoas com risco cardiovascular muito alto*     |
|							 |                                                                          |
|Colesterol não-HDL	     | menor que  160 mg/dl - em pessoas com risco cardiovascular baixo*        |
|(soma do LDL, VLDL e IDL)  | menor que 130 mg/dl - em pessoas com risco cardiovascular intermediário* |
| 			                 | menor que 100 mg/dl - em pessoas com risco cardiovascular alto*          |
| 			                 | menor que 80 mg/dl - em pessoas com risco cardiovascular muito alto*     |

Obs: O risco cardiovascular é calculado pelo médico durante a consulta, e leva em consideração de fatores de risco da pessoa para desenvolver uma doença cardiovascular, como idade avançada, tabagismo, presença de pressão alta, diabetes, doença renal ou outras doenças cardíacas, por exemplo.

Já os triglicerídeos são outro tipo de gordura do organismo, usados como reserva de energia pelo organismo, e quando estão elevados também aumentam o risco de depósitos de aterosclerose nos vasos sanguíneos e aumentam o risco de doenças cardiovasculares.
A tabela de valores normais dos triglicerídeos, por idade, recomendados pela sociedade brasileira de cardiologia são:


| Triglicerídeos		|		Valor de referência          |
|----------------------|------------------------------------|
| Em jejum		 	    |		menor que 150 mg/dl          |
| Sem jejum			|		menor que 175 mg/dl          |

                                         
                                         ')],
                                    ]
                                ]
                            );
                        }

                        break;
                    case 'glicose':

                        //dados para gravar
                        $glicose = $val['glicose'];
                        $hemoglobina_glicada = $val['hemoglobina_glicada'];

                        if ($glicose < 100) {
                            $resultado = 'Normal';
                        } else if ($glicose >= 100 && $glicose <= 125) {
                            $resultado = 'Pré-Diabetes';
                        } else if ($glicose >= 126) {
                            $resultado = 'Diabetes';
                        }

                        //monta o array para insercao
                        $dado['glicose'] = $glicose;
                        $dado['hemoglobina_glicada'] = $hemoglobina_glicada;
                        $dado['classificacao'] = $resultado;
                        $dado['codigo_usuario'] = $codigo_usuario;

                        //verifica se existe dados para edicao
                        $res = $this->UsuariosGlicose->find()->where(['codigo' => $codigo_indicador])->first();
                        //veificacao para edicao
                        if(empty($res)) {
                            $dado['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta novo medidas
                            $res = $this->UsuariosGlicose->newEntity($dado);
                        }
                        else {
                            $res = $this->UsuariosGlicose->patchEntity($res, $dado);
                        }

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosGlicose->save($res)) {
                            $error[] = "Erro ao inserir Glicose";
                        }//fim save
                        else {

                            //dados de retorno
                            $data = array(
                                'label_resultado' => 'Glicose',
                                'resultado' => $resultado,
                                'texto' => 'O resultado é: ' . $glicose . " - " . $resultado,
                                'imagem' => '',
                                'legenda' => '',
                                'entenda' => [
                                    'titulo' => 'Glicose',
                                    'conteudo' => [
                                        ['subtitulo' => '',
                                         'subconteudo' => (new ConvertUtil)->stringToBase64('O exame de glicose, também conhecido como teste da glicose, é feito com objetivo de verificar a quantidade de açúcar no sangue, que recebe o nome de glicemia, e é considerado o principal exame para diagnosticar a diabetes.

Os valores de referência do exame da glicose em jejum são:
- Normal: inferior a 99 mg/dL;
- Pré-diabetes: entre 100 e 125 mg/dL;
- Diabetes: superior a 126 mg/dL em dois dias diferentes.

> Fonte: Sociedade Brasileira de Diabetes

                                        ')],
                                    ]
                                ]
                            );
                        }

                        break;
                    case 'abdominal':
                        //dados para gravar
                        $circ_abdom = $val['circ_abdom'];
                        $circ_quadril = $val['circ_quadril'];
                        $resultado = $circ_abdom / $circ_quadril;

                        //monta o array para insercao
                        $dado['circ_abdom'] = $circ_abdom;
                        $dado['circ_quadril'] = $circ_quadril;
                        $dado['circ_media'] = round($resultado,2);
                        $dado['codigo_usuario'] = $codigo_usuario;

                        //verifica se existe dados para edicao
                        $res = $this->UsuariosAbdominal->find()->where(['codigo' => $codigo_indicador])->first();

                        //veificacao para edicao
                        if(empty($res)) {
                            $dado['data_inclusao'] = date('Y-m-d H:i:s');
                            //seta novo medidas
                            $res = $this->UsuariosAbdominal->newEntity($dado);
                        }
                        else {
                            $res = $this->UsuariosAbdominal->patchEntity($res, $dado);
                        }

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosAbdominal->save($res)) {
                            // debug($imc->errors());
                            $error[] = "Erro ao inserir Medidas Abdominais";
                        }//fim save
                        else {

                            $this->loadModel('UsuariosDados');
                            $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario'=>$codigo_usuario])->first();

                            $dados = $this->UsuariosAbdominal->getResultadoAbdominal($resultado,$usuarios_dados->sexo);

                            //dados de retorno
                            $data = array(
                                'label_resultado' => 'Circunferência abdominal',
                                'resultado' => $dado['circ_media'],
                                'texto' => 'O resultado é: ' . $dado['circ_media'] . " - " . $dados['texto'],
                                'imagem' => '',
                                'legenda' => '',
                                'entenda' => [
                                    'titulo' => 'Circunferência Abdominal',
                                    'conteudo' => [
                                        ['subtitulo' => '',
                                        'subconteudo' => (new ConvertUtil)->stringToBase64('Diversos estudos sugerem que a circunferência abdominal e a relação numérica entre ela e a do quadril permitem prever o risco de adoecer, de forma mais precisa do que o peso ou o índice de massa corpórea **(IMC = peso/ altura x altura)**.

As recomendações atuais são as de que a circunferência abdominal não ultrapasse 102 cm nos homens ou 88 cm nas mulheres. Já a relação circunferência abdominal/circunferência do quadril não deve ser maior do que 1,0 nos homens e 0,85 nas mulheres.

                                        ')],
                                    ]
                                ]
                            );
                        }

                        break;

                    default:
                        $error[] = "Não existe a identificação passada: ".$dados['id'];
                        break;
                }//fim switch

            }//fim isset dados

        }
        else {
            $error[] = "Metodo de envio errado favor enviar post!";
        }

        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            $this->set(compact('data'));
        }



    }//fim setInfos


     /**
     * [delIndicador description]
     *
     * deleta dados do: IMC, Glicose, Pressão Arterial, Abdomen, Colesterol, Tabagismo
     *
     * @param int $codigo_usuario [description]
     */
    public function delIndicador($codigo_usuario)
    {
        //seta a variavel para retorno da mensagem
        $data = "Dados salvo com sucesso!";
        $error = array();

        //verifica se é post
        if ($this->request->is('delete')) {

            //instancia as tabelas de indicadores
            $this->loadModel('UsuariosPressaoArterial');
            $this->loadModel('UsuariosColesterol');
            $this->loadModel('UsuariosGlicose');
            $this->loadModel('UsuariosAbdominal');
            $this->loadModel('UsuariosImc');

            //pega os dados enviados
            $dados = $this->request->getData();

            //para edicao
            $codigo_indicador = null;
            if(isset($dados['codigo'])) {
                $codigo_indicador = (!empty($dados['codigo'])) ? $dados['codigo'] : null;
            }//fim codigo

            //verifica se existe o indice id
            if(isset($dados['id'])) {

                //verifica qual é o tipo que está inserindo
                switch ($dados['id']) {
                    case 'imc':

                        //verifica se existe dados para deletar
                        $res = $this->UsuariosImc->find()->where(['codigo' => $codigo_indicador])->first();

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosImc->delete($res)) {
                            // debug($res->errors());
                            $error[] = "Erro ao deletar IMC";
                        }//fim save
                        else {
                            //dados de retorno
                            $data = "Dado excluido com sucesso";
                        }

                        break;
                    case 'pressao_arterial':
                        //verifica se existe dados para deletar
                        $res = $this->UsuariosPressaoArterial->find()->where(['codigo' => $codigo_indicador])->first();

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosPressaoArterial->delete($res)) {
                            // debug($res->errors());
                            $error[] = "Erro ao inserir Pressão Arterial";
                        }//fim save
                        else {
                            //dados de retorno
                            $data = "Dado excluido com sucesso";
                        }

                        break;
                    case 'colesterol':
                        //verifica se existe dados para deletar
                        $res = $this->UsuariosColesterol->find()->where(['codigo' => $codigo_indicador])->first();

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosColesterol->delete($res)) {
                            // debug($res->errors());
                            $error[] = "Erro ao inserir Colesterol";
                        }//fim save
                        else {
                            //dados de retorno
                            $data = "Dado excluido com sucesso";
                        }

                        break;
                    case 'glicose':

                        //verifica se existe dados para deletar
                        $res = $this->UsuariosGlicose->find()->where(['codigo' => $codigo_indicador])->first();

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosGlicose->delete($res)) {
                            // debug($res->errors());
                            $error[] = "Erro ao inserir Glicose";
                        }//fim save
                        else {
                            //dados de retorno
                            $data = "Dado excluido com sucesso";
                        }

                        break;
                    case 'abdominal':
                        //verifica se existe dados para deletar
                        $res = $this->UsuariosAbdominal->find()->where(['codigo' => $codigo_indicador])->first();

                        //verifica se gravou o resultado inserido
                        if (!$this->UsuariosAbdominal->delete($res)) {
                            // debug($res->errors());
                            $error[] = "Erro ao inserir Medidas Abdominais";
                        }//fim save
                        else {
                            //dados de retorno
                            $data = "Dado excluido com sucesso";
                        }

                        break;

                    default:
                        $error[] = "Não existe a identificação passada: ".$dados['id'];
                        break;
                }//fim switch

            }//fim isset dados

        }
        else {
            $error[] = "Metodo de envio errado favor enviar post!";
        }

        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            $this->set(compact('data'));
        }

    }//fim delIndicador


    
}
