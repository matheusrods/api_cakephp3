<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Core\Configure;

use Cake\Utility\Security as Security;
use Firebase\JWT\JWT;
use App\Utils\Encriptacao;

class ApiController extends AppController
{

    private $paginationAllowedFields = [
        'page'=>'trim|sanitize_string|integer',
        'sort'=>'trim|sanitize_string',
        'direction'=>'trim|sanitize_string',
        'limit'=>'trim|sanitize_string|integer'
    ];

    // public function initialize()
    // {
    //     parent::initialize();

    //     //verfica se precisa descriptografar o valor passado
    //     $var_payload = $this->request->input('json_decode');
    //     if(isset($var_payload->payload)) {
    //         $dados = $this->setDecript($var_payload->payload);
    //         $this->request->data = json_decode($dados,true);
    //     }

    //     // debug($dados);exit;
    // }

    /**
     * [setDecript para descriptografar a chamada]
     * @param [type] $encript [description]
     */
    public function setDecript($encript)
    {
        $encriptacao = new Encriptacao();
        $payload_dencript = $encriptacao->desencriptar($encript);

        return $payload_dencript;
    }

    /**
     * beforeRender callback
     *
     * @param Event $event An Event instance
     * @return null
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Api');
        return null;
    }

    
    /**
     * Função para limpar dados recebidos por url e avaliar paginação
     *
     * @param array $params
     * @param array $allowedParams
     * @return array
     */
    public function sanitizeParams(array $params, array $allowedParams, bool $validateAllowedPagination = true){
        
        $this->debug('Parametros aceitos', $allowedParams);
        $this->debug('Parametros recebidos', $params);
        
        $argsForClean = [];
        
        // se for pra aceitar e validar parametros de paginacao
        if($validateAllowedPagination){
            if(is_array($params)){
                $allowedParams = array_merge($allowedParams, $this->paginationAllowedFields );
            }
        }
        
        if(is_array($params)){
            foreach ($params as $key => $value) {
                if (in_array($key, array_keys($allowedParams)))
                    if(!empty($value))
                        $argsForClean[$key] = $value;
                    if($value == 0) // hack pois "empty" entende que esta vazio
                        $argsForClean[$key] = $value;
            }
        }

        $argsSanitized = $this->sanitize( $argsForClean, $allowedParams); 
       
        $this->debug('Parametros Sanitizados', $argsSanitized);

        $dados = $argsSanitized;

        return $dados;
    }

	/**
	 * Função para acrescentar valores em um array preservando as keys da mesma
	 * https://stackoverflow.com/questions/3353745/how-to-insert-element-into-arrays-at-specific-position?noredirect=1&lq=1
	 *
	 * @param array $arr
	 * @param array $arr_add
	 * @param integer $line posição na linha
	 * @return array
	 */
	public function array_merge_preserve_keys($arr = array(), $arr_add = array(), $line = 0){
		return array_slice($arr, 0, $line, true) +	$arr_add + array_slice($arr, $line, count($arr)-$line, true);
    }
    

    function toBool($var) {
        if (!is_string($var)) return (bool) $var;
        switch (strtolower($var)) {
          case '1':
          case 'true':
          case 'sim':
          case 'on':
          case 'yes':
          case 'y':
            return true;
          default:
            return false;
        }
    }


    public function debug(){

        // @TODO
        // if(MODO::DEV){
        //     return;
        // }

        $mix = func_get_args();

        //if(is_array($mix) && count($mix) == 1){
            if(is_array($mix)){
                $mix = print_r($mix, 1); 
            }
            if(is_object($mix)){
                $mix = print_r((array)$mix, 1); 
            }
    
        //}

        Log::debug($mix);
    }

    /**
     * Função para limpeza de variáveis
     * Criei esta função para embutir limpeza de dados para segurança pois até 3.1 CakePhp não possui,
     * encontrei ouytras libs usando namespace, mas com bugs de booleano então esta serviu neste momento
     * // https://github.com/Wixel/GUMP  ;)
     *
     * @param array $params
     * @param array $allowedParams
     * @return array
     */
    public function sanitize(array $params, array $allowedParams){

        // http://jedistirfry.co.uk/blog/2013-08/third-party-libraries-with-cakephp/
        require_once(dirname(APP).DS.'vendor'.DS.'wixel'.DS.'gump'.DS.'gump.class.php');
        
        $validator = new \GUMP(); 
       
        $params = $validator->sanitize($params);
        $filtered = $validator->filter($params, $allowedParams);

        return $filtered;
    }

    /**
     * Validações de parametros
     *
     * @param array $params
     * @param array $rules
     * @return array
     */
    public function validator(array $params, array $rules){
        
        // http://jedistirfry.co.uk/blog/2013-08/third-party-libraries-with-cakephp/
        require_once(dirname(APP).DS.'vendor'.DS.'wixel'.DS.'gump'.DS.'gump.class.php');

        $validator = new \GUMP(); 
        
        $validated = $validator->is_valid($_POST, $rules);

        return $validated;
    }

    /**
     * Função para remover de um array chaves com valores vazios 
     * afim de evitar conditions erradas nas models
     *
     * @param array $params
     * @return array
     */
    public function removeNullParams(array $params){

        $this->debug('removeNullParams recebe: ', $params);

        foreach ($params as $key => $value) {
            if(isset($params[$key]) && $params[$key] == 0 ){
                continue;
            } 
            if(empty($params[$key]) || is_null($params[$key])){
                unset($params[$key]);
            }
        }
        $this->debug('removeNullParams retorna: ', $params);
        return $params;
    }


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
    public function log_api($entrada,$saida,$status="200",$msg="SUCESSO", $arquivo="MOBILE_API")
    {
        //instancia a model
        $this->LogIntegracao = $this->loadModel('LogsIntegracoes');

        $cod_cliente = 1;
        if(isset($this->cod_cliente)) {
            $cod_cliente = $this->cod_cliente;
        }

        $cod_usuario = 1;
        if(isset($this->cod_usuario)) {
            $cod_usuario = $this->cod_usuario;
        }

        //seta os valores
        $log_integracao['codigo_cliente']          = $cod_cliente;
        $log_integracao['codigo_usuario_inclusao'] = $cod_usuario;
        $log_integracao['descricao']               = $msg;
        $log_integracao['arquivo']                 = $arquivo;
        $log_integracao['conteudo']                = json_encode($entrada);
        $log_integracao['retorno']                 = json_encode($saida);
        $log_integracao['sistema_origem']          = $arquivo;
        $log_integracao['data_arquivo']            = date('Y-m-d H:i:s');
        $log_integracao['status']                  = $status; 
        $log_integracao['tipo_operacao']           = 'I'; //inserido
        $log_integracao['data_inclusao']           = date('Y-m-d H:i:s');

        $log = $this->LogIntegracao->newEntity($log_integracao);

        //inclui na tabela
        $this->LogIntegracao->save($log);

        // debug($log->errors());


    } //fim log_api


    /**
     * Estratégia de apresentação padronizada para respostas de erros
     *
     * @param array $data
     * @return bool
     */
    public function responseError($data = null, array $message = []){
        
        if($data instanceof \PDOException){
            if(Configure::read('debug')){
                Log::debug('Exception');           
                Log::debug(sprintf('%s', $data->getMessage())); 
                Log::debug(sprintf('linha %s - %s', $data->getLine(), $data->getFile()));
            }
            return true;
        }
        // se for uma instância do Cake\ORM é porque a Query foi criada
        if(isset($data) && ($data instanceof \Cake\ORM\Query 
            || isset($data) && is_array($data) && !isset($data['error']))){
            return false;
        }
        
        if(isset($data['error'])){
            $error = (!empty($message)) ? $message : $data['error'];
            $this->set(compact('error'));
            return true;
        }

        return true;
    }


    /**
     * Estratégia de apresentação padronizada para respostas
     *
     * @param array $data
     * @return array
     */
    public function responseOK($data = []){
        
        // se for uma instancia do Cake\ORM
        if(isset($data) && $data instanceof \Cake\ORM\Query){

            $data = $data->toArray();

        } elseif ($data instanceof \Cake\ORM\ResultSet){
            $data = $data->toArray();
        } else {

            if(isset($data['error'])){
                $error = $data['error'];
                $this->set(compact('error'));
                return false;
            }
        }

        $this->set(compact('data'));
        //return true;
    }
    
    /**
     * Estratégia de apresentação padronizada para respostas
     *
     * @param array $data
     * @return array
     */
    public function responseJson($data = []){
               
        // se for uma instancia do Cake\ORM
        if(isset($data) && $data instanceof \Cake\ORM\Query || isset($data) && $data instanceof \Cake\ORM\ResultSet){

            $data = $data->toArray();

        } else {
            
            if(isset($data['error'])){
                $error = $data['error'];
                $this->set(compact('error')); 
                return;
            }
        
            if(!is_array($data)){
                $error = [$data];
                $this->set(compact('error')); 
                return;
            }
        }

        $this->set(compact('data'));
        return;
    }
    
    /**
     * [getUsuario metodo para pegar os dados do token]'
     * @return [type] [description]
     */
    public function getDadosToken()
    {
        $headers = $this->request->getHeaders();
        $dados = array();

        if(isset($headers['Authorization'][0])) {

            $token = substr($headers['Authorization'][0],7);
            $jwt_codificacao = array("typ"=> "JWT","alg"=> "HS256");
            $dados = JWT::decode($token, Security::getSalt(),$jwt_codificacao);
        }

        return $dados;
    }//fim getDadosToken
    
}