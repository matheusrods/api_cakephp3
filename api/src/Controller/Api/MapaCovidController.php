<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

use Cake\Datasource\ConnectionManager;

class MapaCovidController extends ApiController
{
    
    private static $GRAU_CIRCUNFERENCIA_DEFAULT = 40075; // default do app
    private static $RAIO_DEFAULT = 111.32 * 1000; // default do app

    private static $RAIO_DEFAULT_PAIS = 111.32 * 100000; // 111.32 * 100000;
    private static $RAIO_DEFAULT_ESTADOS = 111.32 * 20000; // 111.32 * 10000;
    // private static $RAIO_DEFAULT_CIDADES = 111.32 * 2000; // 111.32 * 1000;
    private static $RAIO_DEFAULT_CIDADES = 111.32 * 70; // 111.32 * 1000;
    // private static $RAIO_DEFAULT_BAIRROS = 111.32 * 20; // 111.32 * 100;
    private static $RAIO_DEFAULT_BAIRROS = 111.32 * 5; // 111.32 * 100;

    private static $APP_DEFAULT_LATITUDE_INICIAL = -13.30467297;
    private static $APP_DEFAULT_LONGITUDE_INICIAL = -52.95781319;

    public function obterOnBoard(){
        
        $data = [];
        
        //seta as mensagens dos onboarding
        $data = array(
            [
                'titulo' => 'Combate a Covid-19',
                'corpo' => 'Mapa colaborativo para combatermos juntos a pandemia da Covid-19.',
                'imagem' => 'https://api.rhhealth.com.br/ithealth/2020/09/20/6B98973E-4A7D-216D-E7AC-25976D522A45.png',
                'step' => 1
            ],
            [
                'titulo' => 'Covid-19 no Brasil',
                'corpo' => 'Acompanhe os casos confirmados no Brasil.',
                'imagem' => 'https://api.rhhealth.com.br/ithealth/2020/09/20/421B1B7C-1E2B-E278-8DE5-6182C0E2C595.png',
                'step' => 2
            ],
            [
                'titulo' => 'Geolocalização',
                'corpo' => 'Confira como está a incidência da Covid-19 por região.',
                'imagem' => 'https://api.rhhealth.com.br/ithealth/2020/09/20/AC1B41D3-AD50-5D59-21C9-95030284A94E.png',
                'step' => 3
            ]
        ); 

        return $this->responseJson($data);
    }

    // api/mapa-covid/pesquisa
    public function obterPesquisa(){

        $data = [];
        $filtros = [];
        
        set_time_limit(0); // TODO: definir um tempo

        // TODO: validar dados que chegam
        $codigo_estado = $this->request->getData('codigo_estado', 0);
        $codigo_cidade = $this->request->getData('codigo_cidade', 0);
        $codigo_bairro = $this->request->getData('codigo_bairro', 0);

        $filtros['estado'] = $this->request->getData('descricao_estado', null);
        $filtros['cidade'] = $this->request->getData('descricao_cidade', null);
        $filtros['bairro'] = $this->request->getData('descricao_bairro', null);

        $filtros['raio'] = $this->request->getData('raio', null); // in miles
      
        $data = $this->obterPayloadDadosMapaCovid( $filtros, 'pesquisa_por_endereco' );

        return $this->responseJson($data);
    }

    // api/mapa-covid/navegacao
    public function obterMapa(){

        $data = [];
        $filtros = [];

        $estado = $this->request->getData('estado', null);
        $cidade = $this->request->getData('cidade', null);
        $bairro = $this->request->getData('bairro', null);
        $regiao = $this->request->getData('regiao', null);

        $filtros['estado'] = $estado;
        $filtros['cidade'] = $cidade;
        $filtros['bairro'] = $bairro;
        $filtros['regiao'] = $regiao;

        $data = $this->obterPayloadDadosMapaCovid( $filtros, 'pesquisa_por_lat_long' );

        return $this->responseJson($data);
    }


    public function distancia($lat1, $lon1, $lat2, $lon2, $unit) 
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }


    // PRIVATE 
    private function obterPayloadDadosMapaCovid(array $filtros, $pesquisa = 'pesquisa_por_lat_long' ){

        // debug($filtros);exit;
        // $this->teste();exit;
        
        $data = [];
        $data_casos = [];
        $agrupador_casos = false;
        $casos_full = []; // payload casos a ser montado

        // $uf = isset($filtros['estado']) ? $this->estadoDescricao($filtros['estado']) : null;
        $uf = isset($filtros['estado']) ? $filtros['estado'] : null;
        $cidade = isset($filtros['cidade']) ? $filtros['cidade'] : null;
        $bairro = isset($filtros['bairro']) ? $filtros['bairro'] : null;
        
        $base_raio = 1000;

        $tmp_agrupar = $this->validarSeAgrupaCasos($filtros);
        
        // -------------------------------
        // PESQUISA POR ENDERECO
        if($pesquisa === 'pesquisa_por_endereco') {
            
            // entendendo que o mapa sempre vai abrir grande
            $filtros['latitude'] = $this->coordenadasEstadosBR('DF')['lat'];
            $filtros['longitude'] = $this->coordenadasEstadosBR('DF')['long'];

            $endereco = 'Brasil';
            // se pesquisar pelo brasil
            if($tmp_agrupar['pais'] === true){
                $agrupador_casos = true;
            }

            // se pesquisar por estado
            $return_distancia = 0;
            
            $agrupador_casos = true;
            
            if($tmp_agrupar['estados'] === true){
                $endereco = $uf;

                $filtros['latitude'] = $this->coordenadasEstadosBR($uf)['lat'];
                $filtros['longitude'] = $this->coordenadasEstadosBR($uf)['long'];

                //buscar a latitude e longitude na tabela de endereco bairro
                $lat_long_estado = $this->buscarDadosPorEstado($uf);

                $codigo = $lat_long_estado['codigo'];
                $latitude = $lat_long_estado['latitude'];
                $longitude = $lat_long_estado['longitude'];
                $latitude_norte = $lat_long_estado['latitude_norte'];
                $longitude_norte = $lat_long_estado['longitude_norte'];
                $latitude_sul = $lat_long_estado['latitude_sul'];
                $longitude_sul = $lat_long_estado['longitude_sul'];

                //grava os dados de norte e sul para calcular a distancia
                if(empty($latitude) && empty($longitude)) {

                    $uf_estado = $this->estadoDescricao($uf);
                    $endereco = $uf_estado;

                    $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);
                    if(isset($tmp['viewport'])) {
                        $latitude_norte = $tmp['viewport']->northeast->lat;
                        $longitude_norte = $tmp['viewport']->northeast->lng;
                        $latitude_sul = $tmp['viewport']->southwest->lat;
                        $longitude_sul = $tmp['viewport']->southwest->lng;
                    }

                    $latitude = (float)$tmp['latitude'];
                    $longitude = (float)$tmp['longitude'];

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul
                        );

                    $this->setEnderecoEstado($codigo, $dados_lat_lng);
                }//fim norte e sul


            } 

            // se pesquisa por cidades
            if($tmp_agrupar['cidades'] === true){                
                $endereco = $uf."+".$cidade;

                //buscar na tabela de estado cidade lat long
                $dados_lat_long = $this->buscarDadosPorEstadoCidade($uf, $cidade);

                $filtros['latitude'] = $dados_lat_long['lat'];
                $filtros['longitude'] = $dados_lat_long['long'];
                $latitude_norte = $dados_lat_long['latitude_norte'];
                $longitude_norte = $dados_lat_long['longitude_norte'];
                $latitude_sul = $dados_lat_long['latitude_sul'];
                $longitude_sul = $dados_lat_long['longitude_sul'];

                //grava os dados de norte e sul para calcular a distancia
                if(empty($dados_lat_long['latitude_norte']) && empty($dados_lat_long['longitude_norte'])) {
                    $uf_estado = $this->estadoDescricao($uf);
                    $endereco = $uf_estado."+".$cidade;

                    $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);
                    
                    if(isset($tmp['viewport'])) {
                        $latitude_norte = $tmp['viewport']->northeast->lat;
                        $longitude_norte = $tmp['viewport']->northeast->lng;
                        $latitude_sul = $tmp['viewport']->southwest->lat;
                        $longitude_sul = $tmp['viewport']->southwest->lng;
                    }

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul,
                        );

                    $codigo = $dados_lat_long['codigo'];

                    $this->setEnderecoCidade($codigo, $dados_lat_lng);
                }//fim norte e sul


            }

            if($tmp_agrupar['bairros'] === true){
                $agrupador_casos = false;
                

                //buscar a latitude e longitude na tabela de endereco bairro
                $lat_long_bairro = $this->buscarDadosPorEstadoCidadeBairro($uf,$cidade,$bairro);

                $codigo_bairro = $lat_long_bairro['codigo'];
                $latitude = $lat_long_bairro['latitude'];
                $longitude = $lat_long_bairro['longitude'];

                $latitude_norte = $lat_long_bairro['latitude_norte'];
                $longitude_norte = $lat_long_bairro['longitude_norte'];
                $latitude_sul = $lat_long_bairro['latitude_sul'];
                $longitude_sul = $lat_long_bairro['longitude_sul'];

                if(empty($latitude) && empty($longitude)) {
                    $uf_estado = $this->estadoDescricao($uf);
                    $endereco = $uf_estado."+".$cidade."+".$bairro;

                    $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);

                    if(isset($tmp['viewport'])) {
                        $latitude_norte = $tmp['viewport']->northeast->lat;
                        $longitude_norte = $tmp['viewport']->northeast->lng;
                        $latitude_sul = $tmp['viewport']->southwest->lat;
                        $longitude_sul = $tmp['viewport']->southwest->lng;
                    }

                    $latitude = (float)$tmp['latitude'];
                    $longitude = (float)$tmp['longitude'];

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul,
                        );

                    $this->setEnderecoBairro($codigo_bairro, $dados_lat_lng);
                }

                $filtros['latitude'] = $latitude;
                $filtros['longitude'] = $longitude;

            }

            $return_distancia = $this->distancia($latitude_norte, $longitude_norte, $latitude_sul, $longitude_sul, 'K') * $base_raio;

            $data['config']['distance'] = $return_distancia;            
            $data['centro']['lat'] = (float)$filtros['latitude'];
            $data['centro']['lng'] = (float)$filtros['longitude'];
    
            // $data['pontos']['agregar'] = $agrupador_casos;
            // $data['debug'] = $tmp_agrupar;
            return $data;
        } 

        // -------------------------------
        // PESQUISA POR NAVEGACAO LAT LONG
        // debug($tmp_agrupar);exit;

        // se for por pais ou uf
        if($tmp_agrupar['pais'] === true){

            $tmp = $this->buscarDadosConfirmados();
            $coords = $this->coordenadasEstadosBR();

            foreach ($tmp as $key => $value) {                
                $coord = $this->coordenadasEstadosBR($value['uf']);
                if(!empty($coord) && is_array($coord)){

                    //pega as lat e long norte e sul da tabela caso nao existe busca no google e grava os dados
                    $dados_lat_lng_norte_sul = $this->getLatLngNorteSul($value['uf']);

                    $tmp[$key] = array_merge($value, $coord);
                    $tmp[$key]['tipo'] = 'brasil';
                    $tmp[$key]['latitude_norte'] = $dados_lat_lng_norte_sul['latitude_norte'];
                    $tmp[$key]['longitude_norte'] = $dados_lat_lng_norte_sul['longitude_norte'];
                    $tmp[$key]['latitude_sul'] = $dados_lat_lng_norte_sul['latitude_sul'];
                    $tmp[$key]['longitude_sul'] = $dados_lat_lng_norte_sul['longitude_sul'];
                    $tmp[$key]['regiao'] = '';
                }
            }
            
            $tmp_lyn = $this->buscarDadosConfirmados(null,null,null,'lyn');
            foreach ($tmp_lyn as $key => $value) {
                $coord = $this->coordenadasEstadosBR($value['uf']);
                if(!empty($coord) && is_array($coord)){

                    //pega as lat e long norte e sul da tabela caso nao existe busca no google e grava os dados
                    $dados_lat_lng_norte_sul = $this->getLatLngNorteSul($value['uf']);

                    $tmp_lyn[$key] = array_merge($value, $coord);
                    $tmp_lyn[$key]['tipo'] = 'lyn';

                    $tmp_lyn[$key]['latitude_norte'] = $dados_lat_lng_norte_sul['latitude_norte'];
                    $tmp_lyn[$key]['longitude_norte'] = $dados_lat_lng_norte_sul['longitude_norte'];
                    $tmp_lyn[$key]['latitude_sul'] = $dados_lat_lng_norte_sul['latitude_sul'];
                    $tmp_lyn[$key]['longitude_sul'] = $dados_lat_lng_norte_sul['longitude_sul'];
                    $tmp_lyn[$key]['regiao'] = '';
                }
            }

            $tmp = array_merge($tmp, $tmp_lyn);

            $casos_full = $tmp;
            $agrupador_casos = true;
        }
        /*else if($tmp_agrupar['estados'] === true) {
            
            $tmp = $this->buscarDadosConfirmados($uf);
            $coord = $this->coordenadasEstadosBR($uf);

            // debug($tmp);exit;

            foreach ($tmp as $key => $value) {
                if(!empty($coord) && is_array($coord)){

                    //pega as lat e long norte e sul da tabela caso nao existe busca no google e grava os dados
                    $dados_lat_lng_norte_sul = $this->getLatLngNorteSul($uf);

                    // debug($value);exit;
                    $dados_brasil = array('confirmado' => $value['confirmado']);
                    $tmp[$key] = array_merge($dados_brasil, $coord);
                    $tmp[$key]['tipo'] = 'brasil';
                    $tmp[$key]['latitude_norte'] = $dados_lat_lng_norte_sul['latitude_norte'];
                    $tmp[$key]['longitude_norte'] = $dados_lat_lng_norte_sul['longitude_norte'];
                    $tmp[$key]['latitude_sul'] = $dados_lat_lng_norte_sul['latitude_sul'];
                    $tmp[$key]['longitude_sul'] = $dados_lat_lng_norte_sul['longitude_sul'];

                    $dados_lyn = array('confirmado' => $value['confirmado_lyn']);
                    $tmp_lyn[$key] = array_merge($dados_lyn, $coord);
                    $tmp_lyn[$key]['tipo'] = 'lyn';
                    $tmp_lyn[$key]['latitude_norte'] = $dados_lat_lng_norte_sul['latitude_norte'];
                    $tmp_lyn[$key]['longitude_norte'] = $dados_lat_lng_norte_sul['longitude_norte'];
                    $tmp_lyn[$key]['latitude_sul'] = $dados_lat_lng_norte_sul['latitude_sul'];
                    $tmp_lyn[$key]['longitude_sul'] = $dados_lat_lng_norte_sul['longitude_sul'];
                }
            }
            
            $tmp = array_merge($tmp, $tmp_lyn);

            $casos_full = $tmp;
            $agrupador_casos = true;
        }*/
        else {
            $agrupador_casos = true;
            $codigos_regiao = null;
            if($tmp_agrupar['bairros'] === true){
                $agrupador_casos = false;
            }
            
            //somente pegar a regiao quando for estado filtro
            if(empty($filtros['cidade']) && empty($filtros['bairro']) && empty($filtros['regiao'])) {
                //busca a regiao
                $regiao = $this->getRegiao($filtros['estado']);

                foreach($regiao AS $dado_regiao) {
                    $codigos[] = $dado_regiao['codigo'];
                }

                $codigos_regiao = implode(',',$codigos);
            }

            $casos_full = $this->buscarDadosSituacaoCovid($filtros,$codigos_regiao, $filtros['regiao']);

            // debug($casos_full);exit;

            //varre os dados para pegar a latitude e longitude norte e sul
            if(!empty($casos_full)) {

                foreach($casos_full  AS $key => $dados) {

                    $uf_dados_lnt_lng = $dados['uf'];
                    $cidade_dados_lnt_lng = $dados['cidade'];
                    $bairro_dados_lnt_lng = (!empty($filtros['bairro'])) ? $filtros['bairro'] : null;

                    $dados_lat_lng_norte_sul = $this->getLatLngNorteSul($uf_dados_lnt_lng,$cidade_dados_lnt_lng,$bairro_dados_lnt_lng);
                    // debug($dados_lat_lng_norte_sul);

                    $casos_full[$key]['latitude_norte'] = $dados_lat_lng_norte_sul['latitude_norte'];
                    $casos_full[$key]['longitude_norte'] = $dados_lat_lng_norte_sul['longitude_norte'];
                    $casos_full[$key]['latitude_sul'] = $dados_lat_lng_norte_sul['latitude_sul'];
                    $casos_full[$key]['longitude_sul'] = $dados_lat_lng_norte_sul['longitude_sul'];

                    if(empty($codigos_regiao)) {
                        $casos_full[$key]['regiao'] = '';
                    }

                }
            }

        }// fim tmp_agrupar

        // debug($filtros); 
        // debug($casos_full); exit;

        // TODO: definir como tratar acentos
        $cidade = ($cidade === 'Brasilia') ? 'Brasília' : $cidade;
        // dd($casos_full, count($casos_full));

        //verifica se tem casos full
        if(!empty($casos_full) && is_array($casos_full)) {
                        
            $tmp = [];
            $tmp_data = [];
            $lyn_count = array();
            $brasil_count = array();

            // debug($casos_full);//exit;

            foreach ($casos_full as $key => $value) {

                // debug($value);exit;

                // debug($value);exit;
                $lat = (float)$value['lat'];
                $lng = (float)$value['long'];
                $tipo = isset($value['tipo']) && $value['tipo'] == "lyn" ?  'lyn' : 'brasil';

                $latitude_norte = (float)$value['latitude_norte'];
                $longitude_norte = (float)$value['longitude_norte'];
                $latitude_sul = (float)$value['latitude_sul'];
                $longitude_sul = (float)$value['longitude_sul'];

                //gera a chave
                $tmpid =  str_replace('-','',str_replace('.','',$lat.$lng));
                
                // debug($tmpid);
                if(!isset($tmp[$tmpid])){
                    $tmp[$tmpid] = [
                        'lat' => $lat, 
                        'lng'=> $lng, 
                        'lyn' => 0, 
                        'brasil' => 0
                    ];
                }

                if(!isset($lyn_count[$tmpid])) {
                    $lyn_count[$tmpid] = 0;
                }

                if(!isset($brasil_count[$tmpid])) {
                    $brasil_count[$tmpid] = 0;
                }

                //conta os casos
                if($tipo == 'lyn') {
                    $lyn_count[$tmpid] += isset($value['confirmado']) ? $value['confirmado'] : 0; // TODO: mostrar confirmado ?

                }
                else if($tipo == "brasil") {
                    $brasil_count[$tmpid] += isset($value['confirmado']) ? $value['confirmado'] : 0; // TODO: mostrar confirmado ?
                }

                $count = ($tipo == "lyn") ? $lyn_count[$tmpid] : $brasil_count[$tmpid];
                

                $dado_cidade = '';
                if(!empty($filtros['cidade']) || !empty($filtros['regiao'])) {
                    if(!empty($cidade)) {
                        $dado_cidade = $filtros['cidade'];
                    }
                    else if(!empty($value['cidade'])) {
                        $dado_cidade = $value['cidade'];
                    }
                }

                $tmp[$tmpid][$tipo] = !empty($count) ? (int)$count : 0;
                $tmp[$tmpid]['distancia'] = $this->distancia($latitude_norte, $longitude_norte, $latitude_sul, $longitude_sul, 'K') * $base_raio;
                $tmp[$tmpid]['estado'] = (!empty($uf)) ? $uf : $value['uf'];
                $tmp[$tmpid]['cidade'] = $dado_cidade;
                $tmp[$tmpid]['bairro'] = $bairro;
                $tmp[$tmpid]['regiao'] = $value['regiao'];
                // $tmp[$tmpid]['count'][$tipo] = !empty($count) ? (int)$count : 0;

                // $tmp_data[$tmpid] =  $tmp[$tmpid];
            }

            // debug($tmp);exit;

            // $tmp_data[] =  $tmp[$tmpid];
            
            //corrigi os indices
            foreach($tmp AS $dados) {
                $data_casos[] = $dados;
            }//fim 

        }// fim casos_full

        // debug($data_casos);exit;

        $data['agregar'] = $agrupador_casos;
        
        $data['pontos']['casos'] = $data_casos;
        
        // TODO: definir de onde pegar estes dados
        $data['visao']['apresenta'] = true;
        
        if($tmp_agrupar['estados'] === true){
            $data['visao']['titulo'] = !empty($cidade) ? "{$cidade} - {$uf}" : $uf;
            $dados = $this->buscarDadosConfirmados($uf, $cidade, $bairro,'tendencias');
        }
        else if($tmp_agrupar['cidades'] === true || $tmp_agrupar['bairros'] === true){
            $data['visao']['titulo'] = "{$cidade} - {$uf}";
            $dados = $this->buscarDadosConfirmados($uf, $cidade, $bairro,'tendencias');
        }
        else {
            $data['visao']['titulo'] = "Brasil";
            $dados = $this->buscarDadosConfirmadosBrasil();
        }
        
        $total = isset($dados[0]['total']) ? $dados[0]['total'] : null;
        $confirmado = isset($dados[0]['confirmado']) ? $dados[0]['confirmado'] : null;
        $recuperados = isset($dados[0]['recuperados']) ? $dados[0]['recuperados'] : null;
        $infectados = isset($dados[0]['infectados']) ? $dados[0]['infectados'] : null;
        $obitos = isset($dados[0]['obitos']) ? $dados[0]['obitos'] : null;

        $data['visao']['casos']['total'] = $total;
        $data['visao']['casos']['recuperados'] = $recuperados;
        $data['visao']['casos']['infectados'] = $infectados;
        $data['visao']['casos']['obitos'] = $obitos;

        $data['tendencia']['apresenta'] = true;
        $data['tendencia']['lateral'] = ['80k','60k','40k','20k'];
        $data['tendencia']['titulo'] = 'Novos casos Covid-19';
        
        $filtros['estado'] = !empty($uf) ? $uf : null;
        $filtros['cidade'] = !empty($cidade) ? $cidade : null;
        $filtros['bairro'] = !empty($bairro) ? $bairro : null;
        $filtros['agrupar'] = $tmp_agrupar;
        
        $data['tendencia'] = $this->obterGraficoTendencias($filtros);
        
        return $data;
    }

    private function obterGraficoTendencias($filtros){
        
        $data = [];
        $data['apresenta'] = true;
        $data['lateral'] = ['80k','60k','40k','20k'];
        $data['titulo'] = 'Novos casos Covid-19';

        $data_fim = date('Y-m-d');
        $data_inicio = date('Y-m-d', strtotime("-30 days", strtotime(str_replace("/", "-", $data_fim))));

        $uf = !empty($filtros['estado']) ? $filtros['estado'] : null;
        $cidade = !empty($filtros['cidade']) ? $filtros['cidade'] : null;
        $tendencia_dados = $this->buscarDadosTendencias($data_inicio, $data_fim, $uf, $cidade);
        
        $tmp_tendencia_dados = [];
        $debug = [];
        
        if(count($tendencia_dados) > 0){
            $numbers = array_column($tendencia_dados, 'confirmado_novo');
            $min = min($numbers);
            $max = max($numbers);
            $debug['min'] = $min;
            $debug['max'] = $max;
            
            $dv_calculo = 1000; 
            
            // CALC QUANDO ERA CASOS CONFIRMADOS
            // if(strlen($max) === 7){
            //     if($max < 1800000){
            //         $dv_calculo = 10000;
            //         $data['lateral'] = ['1.8M','1.6M','1.4M','1.2M'];    
            //     }
            //     if($max < 2800000){
            //         $dv_calculo = 10000;
            //         $data['lateral'] = ['2.8M','2.6M','2.4M','2.2M'];    
            //     }
            //     if($max < 3800000){
            //         $dv_calculo = 10000;
            //         $data['lateral'] = ['3.8M','3.6M','3.4M','3.2M'];    
            //     }
            //     if($max < 4800000){
            //         $dv_calculo = 10000;
            //         $data['lateral'] = ['4.8M','4.6M','4.4M','4.2M'];    
            //     }
            // }

            // if(strlen($max) === 6){
            //     $dv_calculo = 10000;

            //     $data['lateral'] = ['900k','800k','600k','400k'];
            //     if((int)$max < 400000){
            //         $dv_calculo = 4000;
            //         $data['lateral'] = ['400k','300k','200k','100k'];                    
            //     }
            // }

            // if(strlen($max) === 5){
            //     $dv_calculo = 1000;
            //     $data['lateral'] = ['100k','60k','40k','1k'];
            //     if((int)$max > 100000){
            //         $dv_calculo = 500000;
            //         $data['lateral'] = ['500k','250k','50k','1k'];                    
            //     }

            // }

            if(strlen($max) === 4){
                $dv_calculo = 10;
                if((int)$max < 5000){
                    $data['lateral'] = ['5k','3k','1k','500']; // alguns picos de ate 5 k
                }
                if((int)$max < 4000){
                    $data['lateral'] = ['4k','3k','1k','500'];
                }
                if((int)$max < 3000){
                    $data['lateral'] = ['2.8k','1.8k','800','300'];
                }
                if((int)$max < 2000){
                    $data['lateral'] = ['1.8k','1.2k','800','200'];
                }
                if((int)$max < 1000){
                    $data['lateral'] = ['800','400','200','100'];
                }
            }

            if(strlen($max) <= 3){
                $dv_calculo = 100;
                if((int)$max < 1000){
                    $data['lateral'] = ['800','600','400','200'];
                }
                if((int)$max < 500){
                    $data['lateral'] = ['400','200','100','50'];
                }
                if((int)$max < 100){
                    $data['lateral'] = ['80','60','40','20'];
                }
            }
            
            if(strlen($max) <= 2){
                $data['lateral'] = ['80','60','40','20'];
            }
            

            $debug['lateral'] = $data['lateral'];

            $tmp_tendencia_dados = array_map(function($dados) use ($dv_calculo, $max) {
                
                $n = !isset($dados['confirmado_novo']) || !ctype_digit($dados['confirmado_novo']) || (int)$dados['confirmado_novo'] == 0 ? 0 : (strlen($max) <= 2)? (int)$dados['confirmado_novo'] :(int)$dados['confirmado_novo']/$dv_calculo;
                $decimals = 2;
                
                if(strlen($max) <= 2){
                    return (int)substr(round($n), 0, 2);
                }

                if($dv_calculo > 100 ){
                    $decimals = 1;
                    $prec = 1;
                    
                    if (($n * pow(10 , $decimals + 1) % 10 ) == 5){
                        $n -= pow(10 , -($decimals+1));
                    }
                    return (int)substr(round(number_format($n, $decimals)), 0, 2);
                }
                return (int)substr($n, 0, 2);
                return (int)substr(round($n), 0, 2);

            }, $tendencia_dados);

            $debug['dv'] = $dv_calculo;
        }

        $data['dados'] = $tmp_tendencia_dados;

        return $data;
    }

    private function validarSeAgrupaCasos($filtros){
            
        // inicializando 
        $agrupa = [];

        // $agrupa['debug'] = $filtros;

        // nivel de pesquisa
        $agrupa['pais'] = true;
        $agrupa['estados'] = false;
        $agrupa['cidades'] = false;
        $agrupa['bairros'] = false;
        
        $raio_default = self::$RAIO_DEFAULT_PAIS;
        
        // 111.32 <-- mesmo usado no app
        if(!empty($filtros['estado']) && empty($filtros['cidade']) && empty($filtros['bairro'])){
            $raio_default = self::$RAIO_DEFAULT_ESTADOS;
            $agrupa['pais'] = false;
            $agrupa['estados'] = true;
        }

        if(!empty($filtros['estado']) && !empty($filtros['cidade']) && empty($filtros['bairro'])){
            $raio_default = self::$RAIO_DEFAULT_CIDADES;
            $agrupa['pais'] = false;
            $agrupa['estados'] = false;
            $agrupa['cidades'] = true;
        }

        if(!empty($filtros['estado']) && !empty($filtros['cidade']) && !empty($filtros['bairro'])){
            $raio_default = self::$RAIO_DEFAULT_BAIRROS;
            $agrupa['pais'] = false;
            $agrupa['estados'] = false;
            $agrupa['cidades'] = false;
            $agrupa['bairros'] = true;
        }

        return $agrupa;
    }


    
    private function obterEnderecoPorLatLgn($lat, $long){

        $key_server  = 'AIzaSyBEea8ePfWIxg0t3prI96OVgaGfR0YtUWw';
        
        $http = new Client();
        
        $response = $http->get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$long}&sensor=false&key={$key_server}", 'retorna_lat_lgn');
        
        $result = json_decode($response->getStringBody());
        
        return $result;
    }
    
    
    private function obterDistanciaEndereco($origem, $destino){

        $key_server  = 'AIzaSyBEea8ePfWIxg0t3prI96OVgaGfR0YtUWw';
        
        $http = new Client();

        $origem = urlencode($origem);
        $destino = urlencode($destino);

        $response = $http->get("https://maps.googleapis.com/maps/api/distancematrix/json?origins={$origem}&destinations={$destino}&mode=driving&language=pt-BR&sensor=false&key={$key_server}", 'distancia_entre_pontos');
        
        $result = json_decode($response->getStringBody());
        
        return $result;
    }
    
    private function buscarLatitudeLongitudeDaLocalizacao(string $descricao){
        
        $key_server  = 'AIzaSyBEea8ePfWIxg0t3prI96OVgaGfR0YtUWw';

        $data = [];
        
        $http = new Client();
        $descricao = urlencode($descricao);
        $response = $http->get("https://maps.googleapis.com/maps/api/geocode/json?address=={$descricao}&sensor=false&key={$key_server}", 'distancia_entre_pontos');

        /*if(!empty($descricao_bairro)){
        } 
        else if(!empty($descricao_cidade)){
            $response = $http->get("https://maps.googleapis.com/maps/api/geocode/json?address=={$descricao_estado}+{$descricao_cidade}&sensor=false&key={$key_server}", 'distancia_entre_pontos');
        }
        else {
            $response = $http->get("https://maps.googleapis.com/maps/api/geocode/json?address=={$descricao_estado}&sensor=false&key={$key_server}", 'distancia_entre_pontos');
        }*/

        $jsondata = json_decode($response->getStringBody());

        // debug($jsondata);exit;
        
        if($jsondata->status == 'OK'){
            $data['latitude'] = $jsondata->results[0]->geometry->location->lat;
            $data['longitude'] = $jsondata->results[0]->geometry->location->lng;
            $data['viewport'] = $jsondata->results[0]->geometry->viewport;
        }

        return $data;
    }

    /**
     * // [
     *       // 0 => [
     *       //   "uf" => "SP"
     *       //   "cidade" => "São Paulo"
     *       //   "bairro" => ""
     *       //   "confirmado" => "277683"
     *       //   "tipo" => "brasil"
     *       //   "lat" => "-23.5505199"
     *       //   "long" => "-46.6333094"
     *       // ]
     *       // 1 => [
     *       //   "uf" => "SP"
     *       //   "cidade" => "São Paulo"
     *       //   "bairro" => "Parque Grajaú"
     *       //   "confirmado" => "1"
     *       //   "tipo" => "lyn"
     *       //   "lat" => "-23.5505199"
     *       //   "long" => "-46.6333094"
     *       // ]
     */
    private function buscarDadosSituacaoCovid( $filtros , $codigos_regiao = null, $regiao = null){

        
        $uf = isset($filtros['estado']) ? $filtros['estado'] : null;
        $cidade = isset($filtros['cidade']) ? $filtros['cidade'] : null;
        $bairro = isset($filtros['bairro']) ? $filtros['bairro'] : null;
        
        $latitude_min = isset($filtros['latitude_min']) ? $filtros['latitude_min'] : null;
        $latitude_max = isset($filtros['latitude_max']) ? $filtros['latitude_max'] : null;
        $longitude_min = isset($filtros['longitude_min']) ? $filtros['longitude_min'] : null;
        $longitude_max = isset($filtros['longitude_max']) ? $filtros['longitude_max'] : null;
        
        $dados_query = $this->getQueryDados($uf,$cidade);

        $fields_brasil = " b.uf,b.cidade,b.bairro,b.confirmado,'brasil_io' AS tipo,ell.lat,ell.long";
        if(!empty($codigos_regiao)){
            $fields_brasil = " b.uf,b.cidade,b.bairro,b.confirmado,'brasil_io' AS tipo,ell.lat,ell.long,ell.regiao";
        }

        $query =  $dados_query . "
            select 
                {$fields_brasil}
            from cteBrasilIO b
                inner join cteEstCidLatLong ell on b.chave = ell.chave
            where 1 = 1";

        if(!empty($uf)){
            $uf = strtoupper($uf);
            $query = "{$query} AND UPPER(b.uf) = '{$uf}'";
        }

        if(!empty($cidade)){
            $cidade = strtoupper($cidade);
            $query = "{$query} AND UPPER(b.cidade) = '{$cidade}'";
        }

        if(!empty($bairro)){
            $bairro = strtoupper($bairro);
            $query = "{$query} AND UPPER(bairro) = '{$bairro}'";
        }

        if(!empty($codigos_regiao)){
            $query = "{$query} AND ell.codigo IN ({$codigos_regiao})";
        }

        if(!empty($regiao)){
            $query = "{$query} AND ell.regiao = '{$regiao}' ";
        }

        $join = "INNER JOIN cteEstCidLatLong ell on f.uf = ell.estado and f.cidade = ell.cidade";
        $fields = " ell.lat AS lat, ell.long AS long";
        if(!empty($codigos_regiao)){
            $fields = 'ell.lat AS lat, ell.long AS long, ell.regiao';
        }
        if(!empty($bairro)) {
            $join = "";
            $fields = 'f.latitude AS lat,f.longitude AS long';
        }

        $query = "{$query} 

        union all

            select
                f.uf,
                f.cidade,
                f.bairro,
                f.confirmado,
                'lyn' AS tipo,
                {$fields}
            from cteFuncEndTotal f
                {$join}
            where 1 = 1";

        if(!empty($uf)){
            $uf = strtoupper($uf);
            $query = "{$query} AND UPPER(f.uf) = '{$uf}'";
        }

        if(!empty($cidade)){
            $cidade = strtoupper($cidade);
            $query = "{$query} AND UPPER(f.cidade) = '{$cidade}'";
        }

        if(!empty($bairro)){
            $bairro = strtoupper($bairro);
            $query = "{$query} AND UPPER(f.bairro) = '{$bairro}'";
        }

        if(!empty($codigos_regiao)){
            $query = "{$query} AND ell.codigo IN ({$codigos_regiao})";
        }

        if(!empty($regiao)){
            $query = "{$query} AND ell.regiao = '{$regiao}' ";
        }

        // debug($query);exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return $data;
    }


    private function buscarDadosConfirmados( string $uf = null, string $cidade = null, string $bairro = null, $tipo = 'brasil_io'){

        // retornar somente estados
        if(empty($uf)){
            if($tipo == 'brasil_io'){
                $query = "
                    select
                    [state] as uf,
                    place_type,
                    last_available_confirmed as confirmado,
                    last_available_deaths as obitos
                    from brasilio_caso_full 
                    where is_last = 'True'
                    AND place_type = 'state'
                    group by [state], place_type, last_available_confirmed, last_available_deaths
                ";
            } 
            else if($tipo == 'lyn'){            
                $query = "
                    with cteFuncSituacaoCovid AS (
                        select 
                            f.codigo as codigo_funcionario,
                            COUNT(ufc.codigo_grupo_covid) AS confirmados
                        from usuario_grupo_covid ufc 
                            inner join funcionarios f on f.cpf = ufc.cpf
                        WHERE ufc.codigo_grupo_covid IN (3,4)
                        GROUP BY f.codigo
                    ),
                    cteFuncEndTotal AS (
                        select              
                            fe.estado_abreviacao AS uf              
                            ,SUM(fsc.confirmados) AS confirmado
                        from cteFuncSituacaoCovid fsc
                            inner join funcionarios_enderecos fe on fe.codigo = (select top 1 fe_.codigo from funcionarios_enderecos fe_ where codigo_funcionario = fsc.codigo_funcionario order by fe_.codigo desc)
                        group by fe.estado_abreviacao
                    )
                    select uf, confirmado from cteFuncEndTotal;";
            }
        }
        else if($tipo == 'tendencias'){
            $query = "select
                concat('BRASIL',UPPER(state),UPPER(city)) AS chave,
                [state] as uf,
                city as cidade,
                '' AS bairro,
                last_available_confirmed as total,
                last_available_confirmed as confirmado,
                last_available_confirmed as infectados,
                last_available_deaths as obitos
                from brasilio_caso_full where is_last = 'True'";
                
            if(!empty($uf)){
                $uf = strtoupper($uf);
                $query = "{$query} AND [state] = '{$uf}'";
            }   

            if(!empty($cidade)){
                $cidade = strtoupper($cidade);
                $query = "{$query} AND UPPER(city) = '{$cidade}'";
                $query = "{$query} AND place_type = 'city'";
            } else {
                $query = "{$query} AND place_type = 'state'";
            }

        }
        else {

            $query = " with cteFuncSituacaoCovid AS (
                        select 
                            f.codigo as codigo_funcionario,
                            COUNT(ufc.codigo_grupo_covid) AS confirmados
                        from usuario_grupo_covid ufc 
                            inner join funcionarios f on f.cpf = ufc.cpf
                        WHERE ufc.codigo_grupo_covid IN (3,4)
                        GROUP BY f.codigo
                    ),
                    cteFuncEndTotal AS (
                        select              
                            fe.estado_abreviacao AS uf_lyn              
                            ,SUM(fsc.confirmados) AS confirmado_lyn
                        from cteFuncSituacaoCovid fsc
                            inner join funcionarios_enderecos fe on fe.codigo = (select top 1 fe_.codigo from funcionarios_enderecos fe_ where codigo_funcionario = fsc.codigo_funcionario order by fe_.codigo desc)
                         WHERE fe.estado_abreviacao = '{$uf}'
                        group by fe.estado_abreviacao
                    ),
                    cteBrasilIO AS (
                        select
                            concat('BRASIL',UPPER(state),UPPER(city)) AS chave,
                            [state] as uf,
                            city as cidade,
                            '' AS bairro,
                            last_available_confirmed as total,
                            last_available_confirmed as confirmado,
                            last_available_confirmed as infectados,
                            last_available_deaths as obitos
                        from brasilio_caso_full 
                        where is_last = 'True' AND [state] = '{$uf}' 
                            AND place_type = 'state'
                    )

                    select *
                    from cteBrasilIO br
                        left join cteFuncEndTotal lyn on br.uf = lyn.uf_lyn";

        // debug($query);exit;
        }

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');

        return $data;
    }

    private function buscarDadosConfirmadosBrasil( ){

            $query = "select SUM(last_available_confirmed) as total, SUM(last_available_confirmed) as confirmado, SUM(last_available_confirmed) as infectados, SUM(last_available_deaths) as obitos from brasilio_caso_full where is_last = 'True' and place_type = 'state'";

            //executa a query
            $conn = ConnectionManager::get('default');
            $data =  $conn->execute($query)->fetchAll('assoc');

            return $data;

    }

    private function buscarDadosTendencias( $data_inicio = null, $data_fim = null, $uf = null, $cidade = null){

        $query = "select
        [date] as dt,
        sum(last_available_confirmed) as confirmado,
        sum(new_confirmed) as confirmado_novo,
        sum(new_deaths) as obitos_novo  
        from brasilio_caso_full
        where [date] >= '{$data_inicio}' and [date] <= '{$data_fim}'";
        
        if(!empty($uf)){
            $uf = strtoupper($uf);
            $query = "{$query} AND [state] = '{$uf}'";
        }   

        if(!empty($cidade)){
            $query = "{$query} AND city = '{$cidade}'";
            $query = "{$query} AND place_type = 'city'";
        } else {
            $query = "{$query} AND place_type = 'state'";
        }        

        $query = "{$query} group by [date] order by [date]";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');

        return $data;
    }

    private function buscarDadosPorLatLong($lat, $long){

        $query = "select estado, cidade from estado_cidade_lat_long where lat = '{$lat}' AND long = '{$long}'";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return !empty($data) ? $data[0] : null;
    }

    private function buscarDadosPorEstado($uf)
    {

        $query = "select ee.codigo AS codigo, 
                        ee.latitude as latitude, 
                        ee.longitude as longitude,
                        ee.latitude_norte as latitude_norte, 
                        ee.longitude_norte as longitude_norte,
                        ee.latitude_sul as latitude_sul, 
                        ee.longitude_sul as longitude_sul
                    from endereco_estado ee
                    where ee.abreviacao = '{$uf}'";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return !empty($data) ? $data[0] : null;
    }

    private function setEnderecoEstado($codigo, $dados) 
    {

        $query = "UPDATE RHHealth.dbo.endereco_estado 
                SET latitude = {$dados['latitude']}, 
                    longitude={$dados['longitude']},
                    latitude_norte = {$dados['latitude_norte']}, 
                    longitude_norte ={$dados['longitude_norte']},
                    latitude_sul = {$dados['latitude_sul']}, 
                    longitude_sul ={$dados['longitude_sul']}
                WHERE codigo = {$codigo};";
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query);

    }

    private function buscarDadosPorEstadoCidade($uf, $cidade)
    {

        $query = "select codigo, 
                        lat,
                        long,
                        latitude_norte as latitude_norte, 
                        longitude_norte as longitude_norte,
                        latitude_sul as latitude_sul, 
                        longitude_sul as longitude_sul
                    from estado_cidade_lat_long 
                    where estado = '{$uf}' 
                        AND cidade = '{$cidade}'";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return !empty($data) ? $data[0] : null;
    }

    private function setEnderecoCidade($codigo, $dados) 
    {

        $query = "UPDATE RHHealth.dbo.estado_cidade_lat_long 
                    SET latitude_norte = {$dados['latitude_norte']}, 
                        longitude_norte ={$dados['longitude_norte']},
                        latitude_sul = {$dados['latitude_sul']}, 
                        longitude_sul ={$dados['longitude_sul']} 
                    WHERE codigo = {$codigo};";
        // debug($query);
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query);

    }

    private function buscarDadosPorEstadoCidadeBairro($uf, $cidade, $bairro)
    {

        $query = "select eb.codigo AS codigo, 
                        eb.latitude as latitude, 
                        eb.longitude as longitude,
                        eb.latitude_norte as latitude_norte, 
                        eb.longitude_norte as longitude_norte,
                        eb.latitude_sul as latitude_sul, 
                        eb.longitude_sul as longitude_sul
                    from endereco_estado ee
                        inner join endereco_cidade ec on ee.codigo = ec.codigo_endereco_estado
                        inner join endereco_bairro eb on ec.codigo = eb.codigo_endereco_cidade
                    where ee.abreviacao = '{$uf}'
                        AND ec.descricao = '{$cidade}'
                        AND eb.descricao = '{$bairro}'";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return !empty($data) ? $data[0] : null;
    }

    private function setEnderecoBairro($codigo_bairro, $dados) 
    {

        $query = "UPDATE RHHealth.dbo.endereco_bairro 
            SET latitude = {$dados['latitude']}, 
                longitude={$dados['longitude']},
                latitude_norte = {$dados['latitude_norte']}, 
                longitude_norte ={$dados['longitude_norte']},
                latitude_sul = {$dados['latitude_sul']}, 
                longitude_sul ={$dados['longitude_sul']}
            WHERE codigo = {$codigo_bairro};";
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query);

    }


    private function coordenadasEstadosBR( $uf = null){

        $data = [];
        $data['AC'] = ['lat'=>-9.974, 'long'=>-67.8076];
        $data['AL'] = ['lat'=>-9.66625, 'long'=>-35.7351];
        $data['AM'] = ['lat'=>-3.10719, 'long'=>-60.0261];
        $data['AP'] = ['lat'=> 0.0344566, 'long'=> -51.0666];
        $data['BA'] = ['lat'=>-12.9704, 'long'=> -38.5124];
        $data['CE'] = ['lat'=>-3.71839, 'long'=>-38.5434];
        $data['DF'] = ['lat'=>-15.83, 'long'=>-47.86];
        $data['ES'] = ['lat'=>-20.3305, 'long'=>-40.2922];
        $data['GO'] = ['lat'=>-16.6799, 'long'=> -49.255];
        $data['MA'] = ['lat'=>-2.53073, 'long'=>-44.3068];
        $data['MT'] = ['lat'=>-15.5989, 'long'=>-56.0949];
        $data['MS'] = ['lat'=>-20.4435, 'long'=>-54.6478];
        $data['MG'] = ['lat'=>-19.8157, 'long'=>-43.9542];
        $data['PA'] = ['lat'=>-1.45502, 'long'=>-48.5024];
        $data['PB'] = ['lat'=>-7.11532, 'long'=>-34.861];
        $data['PR'] = ['lat'=>-25.4284, 'long'=>-49.2733];
        $data['PE'] = ['lat'=>-8.05428, 'long'=>-34.8813];
        $data['PI'] = ['lat'=>-5.08921, 'long'=>-42.8016];
        $data['RJ'] = ['lat'=>-22.9138851, 'long'=>-43.7261746]; // -22.9138851,-43.7261746
        $data['RN'] = ['lat'=>-5.79448, 'long'=>-35.211];
        $data['RO'] = ['lat'=>-8.76183, 'long'=>-63.902];
        $data['RS'] = ['lat'=>-30.0277, 'long'=>-51.2287];
        $data['RR'] = ['lat'=>2.81954, 'long'=>-60.6714];
        $data['SC'] = ['lat'=>-26.3051, 'long'=>-48.8461];
        $data['SE'] = ['lat'=>-10.9095, 'long'=>-37.0748]; 
        $data['SP'] = ['lat'=>-23.5489, 'long'=>-46.6388]; 
        $data['TO'] = ['lat'=>-10.1689, 'long'=>-48.3317];

        return !empty($uf) ? $data[$uf] : $data;
    }

    private function estadoDescricao( $uf = null){

        $data = [];
        $data['AC'] = 'ACRE';
        $data['AL'] = 'ALAGOAS';
        $data['AM'] = 'AMAZONAS';
        $data['AP'] = 'AMAPÁ';
        $data['BA'] = 'BAHIA';
        $data['CE'] = 'CEARÁ';
        $data['DF'] = 'DISTRITO FEDERAL';
        $data['ES'] = 'ESPÍRITO SANTO';
        $data['GO'] = 'GOIÁS';
        $data['MA'] = 'MARANHÃO';
        $data['MT'] = 'MATO GROSSO';
        $data['MS'] = 'MATO GROSSO DO SUL';
        $data['MG'] = 'MINAS GERAIS';
        $data['PA'] = 'PARÁ';
        $data['PB'] = 'PARAÍBA';
        $data['PR'] = 'PARANÁ';
        $data['PE'] = 'PERNAMBUCO';
        $data['PI'] = 'PIAUÍ';
        $data['RJ'] = 'RIO DE JANEIRO';
        $data['RN'] = 'RIO GRANDE DO NORTE';
        $data['RS'] = 'RIO GRANDE DO SUL';
        $data['RO'] = 'RONDÔNIA';
        $data['RR'] = 'RORAIMA';
        $data['SC'] = 'SANTA CATARINA';
        $data['SE'] = 'SERGIPE';
        $data['SP'] = 'SÃO PAULO';
        $data['TO'] = 'TOCANTINS';

        return !empty($uf) ? $data[$uf] : $data;
    }
    
    /*
    * The valid range:
    *     - in degrees
    *         - latitude -90 and +90
    *         - longitude -180 and +180
    *     - in decimals
    *         - latitude precision=10, scale=8
    *         - longitude precision=11, scale=8
    *
    *  echo isGeoValid('latitude', '-90.00000000');
    *  echo PHP_EOL;
    *  echo isGeoValid('longitude', '-180.00000000');
    */
    function isGeoValid($type, $value)
    {
        $pattern = ($type == 'latitude')
            ? '/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'
            : '/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/';
        
        if (preg_match($pattern, $value)) {
            return true;
        } else {
            return false;
        }
    }

    // echo $this->distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
    // echo $this->distance($filtros['latitude_min'], $filtros['longitude_min'], $filtros['latitude_max'], $filtros['longitude_max'], "K") . " Kilometers<br>";
    // echo $this->distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
          return 0;
        }
        else {
          $theta = $lon1 - $lon2;
          $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
          $dist = acos($dist);
          $dist = rad2deg($dist);
          $miles = $dist * 60 * 1.1515;
          $unit = strtoupper($unit);
      
          if ($unit == "K") {
            return ($miles * 1.609344);
          } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
            return $miles;
          }
        }
    }


    private function buscarDadosConfirmadosPorRaio( $filtros )
    {


        $uf = isset($filtros['estado']) ? $filtros['estado'] : null;
        $cidade = isset($filtros['cidade']) ? $filtros['cidade'] : null;
        $bairro = isset($filtros['bairro']) ? $filtros['bairro'] : null;
        
        $latitude_min = isset($filtros['latitude_min']) ? $filtros['latitude_min'] : null;
        $latitude_max = isset($filtros['latitude_max']) ? $filtros['latitude_max'] : null;
        $longitude_min = isset($filtros['longitude_min']) ? $filtros['longitude_min'] : null;
        $longitude_max = isset($filtros['longitude_max']) ? $filtros['longitude_max'] : null;


        $return_distancia = ($this->distancia($filtros['latitude_max'], $filtros['longitude_max'],$filtros['latitude_min'], $filtros['longitude_min'], 'K')); //* $base_raio;
        // debug($return_distancia);exit;


        //para medir o raio que vai buscar os dados dependendo do delta
        $latitude_delta = $filtros['latitude_delta'];
        $raio = $return_distancia/1000;
        
        //monta a query principal par apegar os casos do brasil io e lyn
        $dados_query = $this->getQueryDados($uf);

        $query = $dados_query . "
        
        select
            b.uf,
            b.cidade,
            b.bairro,
            b.confirmado,
            'brasil_io' AS tipo,
            ell.lat,
            ell.long
        from cteBrasilIO b
            inner join cteEstCidLatLong ell on b.chave = ell.chave
        where 1 = 1";

        if(!empty($uf)){
            $uf = strtoupper($uf);
            $query = "{$query} AND UPPER(b.uf) = '{$uf}'";
        }

        if(!empty($raio)) {
            $query = "{$query} AND (6371 *
                ACOS(
                    COS(RADIANS({$filtros['latitude']})) *
                    cos(radians(ell.lat)) *
                    cos(radians({$filtros['longitude']}) - radians(ell.long)) +
                    sin(radians({$filtros['latitude']})) *
                    sin(radians(ell.lat))
                )) <= {$raio} ";
        }

        $query = "{$query} union all

        select
            f.uf,
            f.cidade,
            f.bairro,
            f.confirmado,
            'lyn' AS tipo,
            f.latitude AS lat,
            f.longitude AS long
        from cteFuncEndTotal f
        where 1 = 1";

        if(!empty($uf)){
            $uf = strtoupper($uf);
            $query = "{$query} AND UPPER(f.uf) = '{$uf}'";
        }

        if(!empty($raio)) {
            $query = "{$query} AND (6371 *
                    ACOS(
                        COS(RADIANS({$filtros['latitude']})) *
                        cos(radians(f.latitude)) *
                        cos(radians({$filtros['longitude']}) - radians(f.longitude)) +
                        sin(radians({$filtros['latitude']})) *
                        sin(radians(f.latitude))
                    )) <= {$raio} ";
        }
        
        // debug($filtros); debug($query);exit;

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return $data;
    }// fim buscarDadosConfirmadosPorRaio

    /**
     * [getQueryDados metodo para pegar os dados do brail io e Lyn, retornnado a query principal]
     * @param  [type] $uf [description]
     * @return [type]     [description]
     */
    private function getQueryDados($uf)
    {

        $query = "WITH cteBrasilIO AS (
            select concat('BRASIL',UPPER(state),UPPER(city)) AS chave, 
                [state] as uf,
                city as cidade, 
                '' AS bairro, 
                last_available_confirmed as confirmado 
            FROM brasilio_caso_full 
            WHERE is_last = 'True'
                AND UPPER([state]) = '{$uf}'
        ),
        cteEstCidLatLong AS (
            select concat('BRASIL',UPPER(estado),UPPER(cidade)) AS chave, * from estado_cidade_lat_long
        ),
        cteFuncSituacaoCovid AS (
            select
                f.codigo as codigo_funcionario,
                COUNT(ufc.codigo_grupo_covid) AS confirmados
            from usuario_grupo_covid ufc 
                inner join funcionarios f on f.cpf = ufc.cpf
            WHERE ufc.codigo_grupo_covid IN (3,4)
            GROUP BY f.codigo
        ),
        cteFuncEndTotal AS (
            select
                fe.estado_abreviacao AS uf              
                ,RHHealth.dbo.ufn_decode_utf8_string(fe.cidade) AS cidade
                ,RHHealth.dbo.ufn_decode_utf8_string(fe.bairro) AS bairro
                ,fe.latitude
                ,fe.longitude
                ,SUM(fsc.confirmados) AS confirmado
            from cteFuncSituacaoCovid fsc
                inner join funcionarios_enderecos fe on fe.codigo = (select top 1 fe_.codigo from funcionarios_enderecos fe_ where codigo_funcionario = fsc.codigo_funcionario order by fe_.codigo desc)
            WHERE UPPER(fe.estado_abreviacao) = '{$uf}'
            group by fe.bairro
                ,fe.cidade
                ,fe.estado_abreviacao
                ,fe.latitude
                ,fe.longitude
        )

        ";

        return $query;

    }// fim getQueryDados($uf)


    /**
     * Description metodo responsavel para buscar os dados de latitude longitude norte e sul para calcular a distancia
     * @param type $uf 
     * @param type|null $cidade 
     * @param type|null $bairro 
     * @return type
     */
    private function getLatLngNorteSul($uf, $cidade = null, $bairro = null)
    {
        //variavel para retornar os dados
        $return = array(
            'latitude' => '',
            'longitude' => '',
            'latitude_norte' => '',
            'longitude_norte' => '',
            'latitude_sul' => '',
            'longitude_sul' => '',
        );

        $latitude = 0;
        $longitude = 0;
        $latitude_norte = 0;
        $longitude_norte = 0;
        $latitude_sul = 0;
        $longitude_sul = 0;

        //valida se está vindo bairro
        if(!empty($bairro)) {
            
            //buscar a latitude e longitude na tabela de endereco bairro
            $lat_long_bairro = $this->buscarDadosPorEstadoCidadeBairro($uf,$cidade,$bairro);

            $codigo_bairro = $lat_long_bairro['codigo'];
            $latitude = $lat_long_bairro['latitude'];
            $longitude = $lat_long_bairro['longitude'];

            $latitude_norte = $lat_long_bairro['latitude_norte'];
            $longitude_norte = $lat_long_bairro['longitude_norte'];
            $latitude_sul = $lat_long_bairro['latitude_sul'];
            $longitude_sul = $lat_long_bairro['longitude_sul'];

            if(empty($latitude) && empty($longitude)) {
                $uf_estado = $this->estadoDescricao($uf);
                $endereco = $uf_estado."+".$cidade."+".$bairro;

                $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);

                if(isset($tmp['viewport'])) {
                    $latitude_norte = $tmp['viewport']->northeast->lat;
                    $longitude_norte = $tmp['viewport']->northeast->lng;
                    $latitude_sul = $tmp['viewport']->southwest->lat;
                    $longitude_sul = $tmp['viewport']->southwest->lng;

                    $latitude = (float)$tmp['latitude'];
                    $longitude = (float)$tmp['longitude'];

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul,
                        );

                    $this->setEnderecoBairro($codigo_bairro, $dados_lat_lng);
                }

            }
        }
        else if(!empty($cidade)) {
            
            //buscar na tabela de estado cidade lat long
            $dados_lat_long = $this->buscarDadosPorEstadoCidade($uf, $cidade);

            $latitude = $dados_lat_long['lat'];
            $longitude = $dados_lat_long['long'];
            $latitude_norte = $dados_lat_long['latitude_norte'];
            $longitude_norte = $dados_lat_long['longitude_norte'];
            $latitude_sul = $dados_lat_long['latitude_sul'];
            $longitude_sul = $dados_lat_long['longitude_sul'];

            //grava os dados de norte e sul para calcular a distancia
            if(empty($dados_lat_long['latitude_norte']) && empty($dados_lat_long['longitude_norte'])) {
                $uf_estado = $this->estadoDescricao($uf);
                $endereco = $uf_estado."+".$cidade;

                $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);
                
                if(isset($tmp['viewport'])) {
                    $latitude_norte = $tmp['viewport']->northeast->lat;
                    $longitude_norte = $tmp['viewport']->northeast->lng;
                    $latitude_sul = $tmp['viewport']->southwest->lat;
                    $longitude_sul = $tmp['viewport']->southwest->lng;

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul,
                        );

                    $codigo = $dados_lat_long['codigo'];

                    $this->setEnderecoCidade($codigo, $dados_lat_lng);
                }


            }//fim norte e sul
        }
        else {

            $endereco = $uf;

            //buscar a latitude e longitude na tabela de endereco bairro
            $lat_long_estado = $this->buscarDadosPorEstado($uf);

            $codigo = $lat_long_estado['codigo'];
            $latitude = $lat_long_estado['latitude'];
            $longitude = $lat_long_estado['longitude'];
            $latitude_norte = $lat_long_estado['latitude_norte'];
            $longitude_norte = $lat_long_estado['longitude_norte'];
            $latitude_sul = $lat_long_estado['latitude_sul'];
            $longitude_sul = $lat_long_estado['longitude_sul'];

            //grava os dados de norte e sul para calcular a distancia
            if(empty($latitude) && empty($longitude)) {

                $uf_estado = $this->estadoDescricao($uf);
                $endereco = $uf_estado;

                $tmp = $this->buscarLatitudeLongitudeDaLocalizacao($endereco);
                if(isset($tmp['viewport'])) {
                    $latitude_norte = $tmp['viewport']->northeast->lat;
                    $longitude_norte = $tmp['viewport']->northeast->lng;
                    $latitude_sul = $tmp['viewport']->southwest->lat;
                    $longitude_sul = $tmp['viewport']->southwest->lng;

                    $latitude = (float)$tmp['latitude'];
                    $longitude = (float)$tmp['longitude'];

                    //monta array para enviar para gravar na base de dados
                    $dados_lat_lng = array(
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'latitude_norte' => $latitude_norte,
                            'longitude_norte' => $longitude_norte,
                            'latitude_sul' => $latitude_sul,
                            'longitude_sul' => $longitude_sul
                        );

                    $this->setEnderecoEstado($codigo, $dados_lat_lng);
                }

            }//fim norte e sul

        }

        $return = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'latitude_norte' => $latitude_norte,
            'longitude_norte' => $longitude_norte,
            'latitude_sul' => $latitude_sul,
            'longitude_sul' => $longitude_sul,
        );

        return $return;


    }//fim getLatLngNorteSul

    private function getRegiao($uf)
    {
        $query = "select codigo, regiao from estado_cidade_lat_long where cidade = regiao and estado = '{$uf}' group by codigo, regiao ";

        //executa a query
        $conn = ConnectionManager::get('default');
        $data =  $conn->execute($query)->fetchAll('assoc');
        
        return $data;
    }


}
