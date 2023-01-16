<?php
namespace App\Utils;

use Cake\ORM\TableRegistry;

/**
 * Classe usada para encriptação.
 */
class Tecnospeed
{
    
    // private $tecnospeed_cnpj = '20183726000114';
    // private $tecnospeed_token = '505112519281e7cea0d5f4ca66b3b0aa';

    //token do ithealth
    public $tecnospeed_cnpj = '20966646000135';
    public $tecnospeed_token = 'ea6590bc357905668e7cfe9b7459b39f';

    private $tecnospeed_url_certificado = 'https://api.tecnospeed.com.br/reinf/v1/certificados';
    private $tecnospeed_url_esocial = 'https://api.tecnospeed.com.br/esocial/v1/evento/enviar/tx2';

    private $url_tecnospeed = 'https://api.tecnospeed.com.br/esocial/v1';
    
    private $cod_cliente;
    private $cod_usuario;

    public function  __construct(){}

    
    /**
     * [log_api description]
     * 
     * METODO PARA GERAR O LOG INTEGRACOES
     * 
     * @param  [type] $status  [description]
     * @param  [type] $entrada [description]
     * @param  [type] $saida   [description]
     * @return [type]          [description]
     */
    public function log_api($entrada,$saida,$status="0",$msg="SUCESSO", $arquivo="API_TECNOSPEED", $model=null, $foreign_key=null)
    {
        //instancia a model
        $this->LogIntegracao = TableRegistry::get('LogsIntegracoes');

        //seta os valores
        $log_integracao['codigo_cliente']          = $this->cod_cliente;
        $log_integracao['codigo_usuario_inclusao'] = $this->cod_usuario;
        $log_integracao['descricao']               = $msg;
        $log_integracao['arquivo']                 = $arquivo;
        $log_integracao['conteudo']                = $entrada;
        $log_integracao['retorno']                 = $saida;
        $log_integracao['sistema_origem']          = $arquivo;
        $log_integracao['data_arquivo']            = date('Y-m-d H:i:s');
        $log_integracao['data_inclusao']           = date('Y-m-d H:i:s');
        $log_integracao['status']                  = $status; 
        $log_integracao['tipo_operacao']           = 'I'; //inserido
        $log_integracao['model']                   = $model;
        $log_integracao['foreign_key']             = $foreign_key;

        // debug($log_integracao);
        //inclui na tabela
        $log = $this->LogIntegracao->newEntity($log_integracao);
        // debug($log);
        //inclui na tabela
        if(!$this->LogIntegracao->save($log)){
            // TODO :: LOGAR EM ARQUIVO ?
            // dd($log->errors());
        }

    } //fim log_api


    /**
     * [tecnospeed_envia_certificado metodo para cadastrar o certificado digital na tecnospeed e relacionar os cnpjs que podem transacionar por ele
     *  segue doc: https://atendimento.tecnospeed.com.br/hc/pt-br/articles/1500008358782-Cadastrar-ceritificado-digital
     * ]
     * @param  [array] $params [array com os dados que vamos enviar para a tecnospeed]
     * @return [type]         [description]
     */
    public function tecnospeed_envia_certificado($codigo_cliente, $codigo_usuario, $codigo_certificado, $params)
    {
        
        //variavel de retorno       
        $retorno = true;

        //validação se tem os parametros
        if(empty($params)) {
            $this->log("MensageriaEsocial: Paramentros necessários para envio do certificado digital",'debug');
            $retorno = false;
            return $retorno;
        }

        //variavel para autenticar
        $auth = array(
            'cnpj_sh: '.$this->tecnospeed_cnpj,
            'token_sh: '.$this->tecnospeed_token
          );
        
        // print "<pre>";
        
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($params['certificado']);
        } else { // 
            $cFile = '@' . realpath($params['certificado']);
        }

        // debug($params['certificado']);
        // debug(array(
        //     'certificado'=> $cFile,
        //     'senha' => $params['senha'],
        //     'cpfCnpjEmpregador' => $params['cpfCnpjEmpregador'],
        //     'email' => $params['email'],
        //     'razaoSocial' => $params['razaoSocial']
        //   ));

        // debug($auth);
        // exit;
        
        //curl para enviar o certificado digital
        $curl = curl_init();

        //chamada no endpoint
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->tecnospeed_url_certificado,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'certificado'=> $cFile,
            'senha' => $params['senha'],
            'cpfCnpjEmpregador' => $params['cpfCnpjEmpregador'],
            'email' => $params['email'],
            'razaoSocial' => $params['razaoSocial']
          ),
          CURLOPT_HTTPHEADER => $auth,
          // CURLOPT_HTTPHEADER => array(
          //   'cnpj_sh: '.$this->tecnospeed_cnpj,
          //   'token_sh: '.$this->tecnospeed_token
          // ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        //transforma o response "retorno da api da tecnospeed" para validacao
        $api_retorno = json_decode($response);
        $status = 0;

        // debug($response);
        // debug($api_retorno);
        // exit;

        $msg_retorno = "SUCESSO";
        if(isset($api_retorno->error)) {
            $status = 1;
            $msg_retorno = "ERROR";
            $retorno = false;
        }
        else {
            $retorno = $api_retorno->data->data->handle;
        }

        // $retorno = '123';
        // $api_retorno = '123';

        $this->cod_cliente = $codigo_cliente;
        $this->cod_usuario = $codigo_usuario;

        //seta o log da api
        $this->log_api(
            json_encode($params), json_encode($api_retorno), $status, $msg_retorno, 'API_TECNOSPEED_CERTIFICADO','IntEsocialCertUnidade',$codigo_certificado
        );

        return $retorno;

    }//fim tecnospeed

    /**
     * Metodo para separar os dados para o cadastro do certificados seguindo a documentação nova da tecnospeed: 
     * https://atendimento.tecnospeed.com.br/hc/pt-br/articles/7433589182103
     * 
     * 1º cadastro dos empregadores
     * 2º cadastro do certificado
     * 3º vinculo do certificado com os empregadores
     * 
     * Realizar o log em cada chamada, gerar o retorno para o sistema que consumiu o cadastro do certificado inicialmente o IT Health
     * 
     */
    public function tecnospeed_cadastro_certificados($dados)
    {

        if(empty($dados)) {
            $this->log("MensageriaEsocial: Paramentros necessários para envio do certificado digital",'debug');
            $retorno = false;
            return $retorno;
        }

        if(isset($dados['params'])) {

            /**
             * Trecho para enviar os empregadores
             */
            $cnpjs_integ = explode(",",$dados['params']['cpfCnpjEmpregador']);

            //instancia a model
            $this->Cliente = TableRegistry::get('Cliente');
            foreach($cnpjs_integ as $key_cnpj => $cnpj ) {

                $dado_cliente = $this->Cliente->find()->select(['razao_social'])->where(['codigo_documento' => $cnpj,'tipo_unidade' => 'F'])->first();
                //verifica se tem dados para pegar a razao social, que são unidades Fiscais
                if(empty($dado_cliente)) {
                    unset($cnpjs_integ[$key_cnpj]);
                    continue;
                }
                $dado_cliente = $dado_cliente->toArray();
                // debug(array($cnpj,$dado_cliente));
                $razao_social = $dado_cliente['razao_social'];

                $empregador = $this->tecnospeed_empregador($dados['codigo_cliente'],$dados['codigo_usuario'], $dados['codigo_certificado'], $cnpj, $razao_social);
            }//fim foreach
            /**
             * Fim enviar os empregadores
             */ 

            //chama o metodo para enviar o certificado para a base de dados da tecnospeed
            $codigo_handle = $this->tecnospeed_certicado($dados['codigo_cliente'],$dados['codigo_usuario'], $dados['codigo_certificado'], $dados['params']);

            //vincula o certificado com os clientes
            $vinclulo_certificado_clientes = $this->tecnospeed_vincular_certificado_cliente($dados['codigo_cliente'],$dados['codigo_usuario'], $dados['codigo_certificado'], $codigo_handle, implode(",",$cnpjs_integ));

            $retorno = $codigo_handle;

        }//fim veificacao se tem params

        return $retorno;
    }//fim tecnospeed_certificados 

    /**
     * Metodo para enviar os dados para registro dos empregadores
     * https://atendimento.tecnospeed.com.br/hc/pt-br/articles/4422011694231-Rota-Cadastrar-Empregador-eSocial
     * 
     * cnpj e razao_social é obrigatorio na tecnospeed
     * 
     * @param $codigo_cliente -> codigo do cliente da base de dados principal geralmente o grupo economico
     * @param $codigo_usuario -> codigo do usuario logado que está fazendo a requisicao
     * @param $codigo_certificado -> codigo da tabela int_esocial_certificado 
     * @param $cnpj -> cnpj para cadstro na tecnospeed
     * @param $razao_social -> razao social do cnpj passado
     */
    public function tecnospeed_empregador($codigo_cliente, $codigo_usuario, $codigo_certificado, $cnpj, $razao_social)
    {

        //variavel de retorno       
        $retorno = true;

        //verificacoes
        if(empty($codigo_cliente)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo cliente",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($codigo_usuario)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo usuario",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($codigo_certificado)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo certificado",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($cnpj)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para cnpj",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($razao_social)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para razao_social",'debug');
            $retorno = false;
            return $retorno;
        }

        //variavel para autenticar
        $auth = array(
            "Content-Type: application/x-www-form-urlencoded",
            'cnpj_sh: '.$this->tecnospeed_cnpj,
            'token_sh: '.$this->tecnospeed_token,
        );
        //url da tecnospeed para enviar o empregador
        $url = $this->url_tecnospeed."/empregadores";

        $dados_post = "empregador=".$cnpj."&razaosocial=".$razao_social;

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $dados_post,
          CURLOPT_HTTPHEADER => $auth,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        //transforma o response "retorno da api da tecnospeed" para validacao
        $api_retorno = json_decode($response);
        $status = 0;

        // debug($response);
        // debug($api_retorno);
        // exit;

        $msg_retorno = "SUCESSO";
        if(isset($api_retorno->error)) {
            $status = 1;
            $msg_retorno = "ERROR";
            $retorno = false;
        }
        else {
            $retorno = "SUCESSO"; //$api_retorno->data->status;
        }

        $this->cod_cliente = $codigo_cliente;
        $this->cod_usuario = $codigo_usuario;

        //seta o log da api
        $params = array('codigo_cliente'=>$codigo_cliente,'codigo_usuario'=>$codigo_usuario,'codigo_certificado'=>$codigo_certificado,'cnpj'=>$cnpj,'razao_social'=>$razao_social);
        $this->log_api(
            json_encode($params), json_encode($api_retorno), $status, $msg_retorno, 'API_TECNOSPEED_EMPREGADORES','IntEsocialCertUnidade',$codigo_certificado
        );

        return $retorno;
    }// fim tecnospeed_empregador

    /**
     * Metodo para enviar o arquivo do certificado
     * 
     * https://atendimento.tecnospeed.com.br/hc/pt-br/articles/4405550960663-Rota-Cadastrar-certificado-digital-eSocial
     * 
     * @param $codigo_cliente -> codigo do cliente da base de dados principal geralmente o grupo economico
     * @param $codigo_usuario -> codigo do usuario logado que está fazendo a requisicao
     * @param $codigo_certificado -> codigo da tabela int_esocial_certificado 
     * @param $param -> array com dados do caminho do certificado, cnpjs relacionados
     */
    public function tecnospeed_certicado($codigo_cliente, $codigo_usuario, $codigo_certificado, $params)
    {

        //variavel de retorno       
        $retorno = true;

        //validação se tem os parametros
        if(empty($params)) {
            $this->log("MensageriaEsocial: Paramentros necessários para envio do certificado digital",'debug');
            $retorno = false;
            return $retorno;
        }

        //instancia a model
        $this->Cliente = TableRegistry::get('Cliente');
        $dado_cliente = $this->Cliente->find()->select(['codigo_documento'])->where(['codigo' => $codigo_cliente])->first()->toArray();
        $cnpj_empregador = $dado_cliente['codigo_documento'];


        //variavel para autenticar
        $auth = array(
            'cnpj_sh: '.$this->tecnospeed_cnpj,
            'token_sh: '.$this->tecnospeed_token,
            'empregador:'.$cnpj_empregador
          );
        
        // print "<pre>";
        
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($params['certificado']);
        } else { // 
            $cFile = '@' . realpath($params['certificado']);
        }

        // debug($params['certificado']);
        // debug(array(
        //     'certificado'=> $cFile,
        //     'senha' => $params['senha'],
        //     'cpfCnpjEmpregador' => $params['cpfCnpjEmpregador'],
        //     'email' => $params['email'],
        //     'razaoSocial' => $params['razaoSocial']
        //   ));

        // debug($auth);
        // exit;
        
        //curl para enviar o certificado digital
        $curl = curl_init();

        $url_certificado = $this->url_tecnospeed."/certificados";

        //chamada no endpoint
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url_certificado,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'certificado'=> $cFile,
            'senha' => $params['senha'],
            'email' => $params['email'],
            'apelido' => "certificado: " . $params['razaoSocial']
          ),
          CURLOPT_HTTPHEADER => $auth,
          // CURLOPT_HTTPHEADER => array(
          //   'cnpj_sh: '.$this->tecnospeed_cnpj,
          //   'token_sh: '.$this->tecnospeed_token
          // ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        //transforma o response "retorno da api da tecnospeed" para validacao
        $api_retorno = json_decode($response);
        $status = 0;

        // debug($response);
        // debug($api_retorno);
        // exit;

        $msg_retorno = "SUCESSO";
        if(isset($api_retorno->error)) {
            $status = 1;
            $msg_retorno = "ERROR";
            $retorno = false;
        }
        else {
            $retorno = $api_retorno->data->handle;
        }

        // $retorno = '123';
        // $api_retorno = '123';

        $this->cod_cliente = $codigo_cliente;
        $this->cod_usuario = $codigo_usuario;

        //seta o log da api
        $this->log_api(
            json_encode($params), json_encode($api_retorno), $status, $msg_retorno, 'API_TECNOSPEED_CERTIFICADO','IntEsocialCertUnidade',$codigo_certificado
        );

        return $retorno;
    }//fim tecnospeed_certificado


    /**
     * Metodo para relacionar as empresas carregadas e o certificado carregado
     * 
     * https://atendimento.tecnospeed.com.br/hc/pt-br/articles/4421929182743-Rota-Vincular-certificado-digital
     * 
     * @param $codigo_cliente -> codigo do cliente da base de dados principal geralmente o grupo economico
     * @param $codigo_usuario -> codigo do usuario logado que está fazendo a requisicao
     * @param $codigo_certificado -> codigo da tabela int_esocial_certificado 
     * @param $codigo_handle -> codigo de cadastro do certificado no berau tecnospeed
     * @param $cnpjs -> cnpjs relacionados para vinculo no certificado cadastrado
     */
    public function tecnospeed_vincular_certificado_cliente($codigo_cliente, $codigo_usuario, $codigo_certificado, $codigo_handle, $cnpjs)
    {

        //variavel de retorno       
        $retorno = true;

        //verificacoes
        if(empty($codigo_cliente)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo cliente",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($codigo_usuario)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo usuario",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($codigo_certificado)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo certificado",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($codigo_handle)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para codigo handle, codigo de retorno do certificado cadastrado na tecnospeed",'debug');
            $retorno = false;
            return $retorno;
        }

        if(empty($cnpjs)) {
            $this->log("Tecnospeed Empregador: Necessário o valor para 1 ou mais cnpjs separados por virgula",'debug');
            $retorno = false;
            return $retorno;
        }

        //pega o codigo documento do empregador
        //instancia a model
        $this->Cliente = TableRegistry::get('Cliente');
        $dado_cliente = $this->Cliente->find()->select(['codigo_documento'])->where(['codigo' => $codigo_cliente])->first()->toArray();
        $cnpj_empregador = $dado_cliente['codigo_documento'];

        //variavel para autenticar
        $auth = array(
            "Content-Type: application/x-www-form-urlencoded",
            'cnpj_sh: '.$this->tecnospeed_cnpj,
            'token_sh: '.$this->tecnospeed_token,
            "empregador: ".$cnpj_empregador,
        );

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => $this->url_tecnospeed."/certificados/".$codigo_handle,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "cpfCnpjEmpregador=".$cnpjs,
          CURLOPT_HTTPHEADER => $auth,
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        //transforma o response "retorno da api da tecnospeed" para validacao
        $api_retorno = json_decode($response);
        $status = 0;

        // debug($response);
        // debug($api_retorno);
        // exit;

        $msg_retorno = "SUCESSO";
        if(isset($api_retorno->error)) {
            $status = 1;
            $msg_retorno = "ERROR";
            $retorno = false;
        }
        else {
            $retorno = "SUCESSO"; //$api_retorno->data->status;
        }

        $this->cod_cliente = $codigo_cliente;
        $this->cod_usuario = $codigo_usuario;

        //seta o log da api
        $params = array('codigo_cliente'=>$codigo_cliente,'codigo_usuario'=>$codigo_usuario,'codigo_certificado'=>$codigo_certificado,'codigo_handle'=>$codigo_handle,'cnpjs'=>$cnpjs);
        $this->log_api(
            json_encode($params), json_encode($api_retorno), $status, $msg_retorno, 'API_TECNOSPEED_VINCULO_CERTIFICADO','IntEsocialCertUnidade',$codigo_certificado
        );

        return $retorno;

    }//fim tecnospeed_relacionar_certificado_cliente

}