<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Core\Configure;
use Cake\Collection\Collection;
use Exception;

/**
 * Funções comuns relativas aos produtos P.O.S
 * 
 */
abstract class PosApiController extends ApiController
{
    public $Pos = null;
    
    /**
     * codigo de erro padrão para aplicações P.O.S
     *
     * @var integer
     */
    public static $POS_ERROR_CODE = 1; 

    
    public function initialize()
    {
        parent::initialize();

        $this->Pos = $this->loadModel('Pos');
    
    }

    /**
     * Retorna codigo_cliente da Matriz de um usuário
     * Para usuários multi-cliente obter a matriz/grupo econômico a qual ele foi cadastrado.
     *
     * @param integer $codigo_usuario
     * @return int    retorna o código da matriz
     */
    public function obterCodigoClienteMatrizDoUsuario(int $codigo_usuario){

        try {
            return $this->Pos->obterCodigoMatrizPeloCodigoUsuario($codigo_usuario);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Retorna o código da Matriz de acordo com o código da Unidade fornecida
     *
     * @param integer $codigo_unidade
     * @return int    retorna o código da matriz
     */
    public function obterCodigoClienteMatrizDaUnidade(int $codigo_unidade){

        try {
            return $this->Pos->obterCodigoMatrizPeloCodigoFilial($codigo_unidade);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public function obterAlocacoesUsuarioAutenticado(){
        
        try {
            $codigo_usuario = $this->obterCodigoUsuarioAutenticado();

            if(empty($codigo_usuario)){
                throw new Exception('Usuário não autenticado');
            }

            $arrLocacoes = $this->Pos->obterAlocacoesDoUsuario($codigo_usuario);

            return $arrLocacoes;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Valida se a pesquisa de um cliente faz parte das alocações dele
     *
     * @param [type] $codigo_cliente
     * @return void
     */    
    public function validaCodigoClienteUsuarioAutenticado($codigo_cliente){
     
        try {
            $arrLocacoes = $this->obterAlocacoesUsuarioAutenticado($codigo_cliente);
            $collection = new Collection($arrLocacoes);
            return $collection->contains($codigo_cliente);
        } catch (Exception $e) {
                
            throw $e;
        }
    }

    public function obterCodigoUsuarioAutenticado()
    {
        try {

            $usuarioData = $this->getDadosToken();

            $condigo_usuario = isset($usuarioData->codigo_usuario) ? $usuarioData->codigo_usuario : null;
                
        } catch (Exception $e) {
            
            throw $e;
        }

        return $condigo_usuario;
    }

    /**
     * Avalia se existe endereço de url para retornar uma imagem
     *
     * @param string $urlAnexo
     * @return string|null  Retorna uma string de url ou nulo
     */
    public function avaliaUrlAnexo(string $urlAnexo = null)
    {
        if(!empty($urlAnexo)){
            $urlAnexo = (strpos($urlAnexo, FILE_SERVER) !== false) ? $urlAnexo : FILE_SERVER . $urlAnexo;
        }

        return $urlAnexo;
    }

    
    public function responseMessageError(string $message)
    {

        $data = [
            'error' => [
                'message' => $message,
            ],
        ];

        $this->set(compact('data'));
        return;
    }


    /**
     * Estratégia de apresentação padronizada para respostas
     *
     * @param array|string $data
     * @param array $formErrorValidations
     * 
     * @return Cake\View\Json
     */
    public function responseMessage($data = [], $formErrorValidations = []){
               
        // se for uma instancia do Cake\ORM
        if(isset($data) && $data instanceof \Cake\ORM\Query 
            || isset($data) && $data instanceof \Cake\ORM\ResultSet
            || isset($data) && $data instanceof \Cake\ORM\Entity){

            // transforma em array
            $data = $data->toArray();

            $this->set(compact('data'));
            return;
        } 

        if(isset($data) && $data instanceof Exception){
            
            $message = ($data->getCode() == 1) ? $data->getMessage() : 'Erro interno no servidor.';

            $error = [
                'message' => $message,
                'code' => $data->getCode()                
            ];
            
            if($data->getCode() != 1 
                && Configure::read('debug')){
                $error['debug'] = $data->getMessage();
            }

            $this->set(compact('error'));
            return;
        }
        
        if(is_string($data)){
            
            if(!empty($formErrorValidations)){
                $error = [
                    'message' => $data,
                    'form_errors' => $formErrorValidations                    
                ];

                $this->set(compact('error'));
                return;

            } else {
                $data = ['message' => $data];
            }

            $this->set(compact('data'));
            return;
        }

        if(is_array($data)){

            if(isset($data['error'])){
                $error = $data['error'];
                $this->set(compact('error')); 
                return;
            }
        }


        $this->set(compact('data'));
        return;
    }

    
}


