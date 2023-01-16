<?php

namespace App\Middleware;

use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Log\Log;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;
use Cake\Core\Configure;

class LogsMiddleware {

    public function __invoke($request, $response, $next)
    {

        $response = $next($request, $response);
        
        try {
            $this->avaliarRequestResponse($request, $response);
            
        } catch (\Exception $e) {
            Log::error('LogsMiddleware :: Ocorreu uma falha ao tentar avaliar Request e Response : '.print_r($e, 1));
        }
        
        return $response; 
    }

    private function avaliarRequestResponse(ServerRequest $request, Response $response){
        
        $requestParsed = Router::parseRequest($request);
        
        $matchedRoute = $requestParsed['_matchedRoute'];

        $controller = isset($requestParsed['controller']) && !empty($requestParsed['controller']) ? $requestParsed['controller'] : '';
        $action = isset($requestParsed['action']) && !empty($requestParsed['action']) ? $requestParsed['action'] : '';
        
        // não grava log nestas condições
        if(strtoupper($controller) === 'STATUS' || strpos($matchedRoute, '/') === false){
            return;
        }

        // define sistema origem
        // $text = str_replace('/', ' ', $matchedRoute);
        // $text = str_replace('*', '', $text);
        // $text = Inflector::classify($text);
        // $text = Inflector::underscore($text);
        // $text = strtoupper($text);
        // $method = isset($requestParsed['_method']) && !empty($requestParsed['_method']) ? is_array($requestParsed['_method']) ? json_encode($requestParsed['_method']): $requestParsed['_method'] : '';
        // $text = $text .'-('. $controller.'::'.$action.')';


        $text = Inflector::classify($controller.' '.$action);
        $text = Inflector::underscore($text);
        $text = strtoupper($text);

        $sistema_origem = substr($text,0,99); // 100

        // define arquivo
        $arquivo = substr($request->getRequestTarget(),0,49); // 50

        $uri = $request->getUri();
        $path = $uri->getPath();
        $passedArgs = $request->getParam('pass');
        $params = $request->getQueryParams();
        // $data = $request->getData();
        $query = $uri->getQuery();

        //$host = $uri->getHost();
        $headers = $request->getHeaders();

        
            $r = \Zend\Diactoros\Request\ArraySerializer::toArray($request);

            // $request_stream = isset($r['body']) ? $r['body'] : null;
            $data = isset($r['body']) ? $r['body'] : null;;
        
        
        // conteudo
        // $request_stream = isset($r['body']) ? $r['body'] : null;

        $conteudo = [
            'uri' => $uri,
            'args'=> $passedArgs,
            'params' => $params,
            'query' => $query,
            'headers' => $headers,
            'data' => $data,
            'path' => $path,
            // 'stream' => $request_stream
        ];

        // dd($conteudo);
        
        $retorno = $response->getBody();

        $status = $response->getStatusCode(); // http

        if($status >= 200 && $status <= 299) {
            $log_status = '1';
            $ret_mensagem = 'SUCESSO';
        }
        else {
            $log_status = '0';
            $ret_mensagem = 'ERROR';
        }
        
        if(Configure::read('debug')){
            Log::debug('___________________________________________________________');
            Log::debug('REQUEST: -> '. $sistema_origem);
            Log::debug('[ROUTE]: '.json_encode($requestParsed));
            Log::debug('[CONTEUDO]: '.json_encode($conteudo));
            Log::debug('RESPONSE: ('.$status.') ');
            Log::debug('[RETORNO]: '.$retorno);
            Log::debug('___________________________________________________________');
        }
        
        // componente para log da api
        $this->log_api(json_encode($conteudo, JSON_PRETTY_PRINT), json_encode(json_decode($retorno), JSON_PRETTY_PRINT), $log_status, $ret_mensagem, $arquivo, $sistema_origem);        
            
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
    public function log_api($entrada,$saida,$status="0",$msg="SUCESSO", $arquivo="MOBILE_API", $sistema_origem="MOBILE_API")
    {
        
        //instancia a model
        $this->LogIntegracao = TableRegistry::get('LogsIntegracoes');

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
        $log_integracao['conteudo']                = $entrada;
        $log_integracao['retorno']                 = $saida;
        $log_integracao['sistema_origem']          = $sistema_origem;
        $log_integracao['data_arquivo']            = date('Y-m-d H:i:s');
        $log_integracao['status']                  = $status; 
        $log_integracao['tipo_operacao']           = 'I'; //inserido
        $log_integracao['data_inclusao']           = date('Y-m-d H:i:s');
        
        $log = $this->LogIntegracao->newEntity($log_integracao);
        
        //inclui na tabela
        if(!$this->LogIntegracao->save($log)){
            // TODO :: LOGAR EM ARQUIVO ?
            // dd($log->errors());
        }

    } //fim log_api
}