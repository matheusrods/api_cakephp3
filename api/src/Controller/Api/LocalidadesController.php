<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\FilterUtil;

class LocalidadesController extends ApiController
{
    /**
     * Obter estados de uma localidade
     * 
     * @param string $q Pesquisa por descrição
     * @return array
     */
    public function obterEstados(){

        $data = [];
        $conditions = [];

        $q = (new FilterUtil)->toAlpha(urldecode($this->request->getQuery('q')));

        $this->loadModel('EnderecoEstado');
        
        $fields = [
            'codigo' => 'codigo', 
            'abreviacao' => 'abreviacao',
            'descricao' => 'descricao'
        ];

        $conditions = ['codigo_endereco_pais' => 1];
        
        if(!empty($q)){
           $conditions["RHHealth.dbo.ufn_decode_utf8_string(descricao) LIKE"] = "%{$q}%";
        }
        
        try {

            $data = $this->EnderecoEstado->find()
            ->where($conditions);

            // $data->select($fields);

            $data->toArray();

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter um estado de uma localidade
     * 
     * @param int $codigo
     * @return array
     */
    public function obterEstado($codigo){

        $data = [];
        $conditions = [];

        $codigo = (new FilterUtil)->toInt($codigo);

        $this->loadModel('EnderecoEstado');
        
        $fields = [
            'codigo' => 'codigo', 
            'abreviacao' => 'abreviacao',
            'descricao' => 'descricao'
        ];

        $conditions = ['codigo_endereco_pais' => 1];
        
        if(!empty($codigo)){
           $conditions["codigo"] = "{$codigo}";
        }
        
        try {

            $data = $this->EnderecoEstado->find()
            ->where($conditions);

            // $data->select($fields);

            $data->toArray();

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter um cidades de localidades
     * 
     * @param int $codigo
     * @return array
     */
    public function obterCidades(){
        $data = [];
        $conditions = [];
        
        $this->request->allowMethod('get');

        $codigo_estado = (new FilterUtil)->toInt($this->request->getQuery('codigo_estado'));
        
        $q = (new FilterUtil)->toAlpha(urldecode($this->request->getQuery('q')));
        
        $this->loadModel('EnderecoCidade');
        
        $fields = [
            'codigo' => 'codigo', 
            'abreviacao' => 'abreviacao',
            'descricao' => 'descricao',
            'codigo_estado' => 'codigo_endereco_estado'
        ];

        $conditions = [];
        
        if(!empty($codigo_estado)){   
            $conditions['codigo_endereco_estado'] = $codigo_estado;
        }

        if(!empty($q)){
           $conditions["descricao LIKE"] = "%{$q}%";
        }

        $conditions['invalido'] = '0';
        
        try {

            $data = $this->EnderecoCidade->find()
            ->where($conditions);

            // $data->select($fields);

            if(empty($codigo_estado) || empty($q)){
                $this->paginate($data);
            }

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter uma cidade de uma localidade
     * 
     * @param string $q Pesquisa por descrição
     * @return array
     */
    public function obterCidade($codigo){

        $data = [];
        $conditions = [];

        $codigo = (new FilterUtil)->toInt($codigo);

        $this->loadModel('EnderecoCidade');
        
        $fields = [];
       
        if(!empty($codigo)){
           $conditions["codigo"] = "{$codigo}";
        }
        
        try {

            $data = $this->EnderecoCidade->find()
            ->where($conditions);

            // $data->select($fields);
            
            $data->toArray();

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter bairros de localidades
     * 
     * @param string $q Pesquisa por descrição de um bairro
     * @param string $codigo_cidade Pesquisa bairros de uma cidade
     * @return array
     */
    public function obterBairros(){
        $data = [];
        $conditions = [];

        $codigo_cidade = (new FilterUtil)->toInt($this->request->getQuery('codigo_cidade'));
        $q = (new FilterUtil)->toAlpha(urldecode($this->request->getQuery('q')));

        $this->loadModel('EnderecoBairro');
        
        $fields = [
            'codigo' => 'codigo', 
            'abreviacao' => 'abreviacao',
            'descricao' => 'descricao',
            'codigo_cidade' => 'codigo_endereco_cidade'
        ];

        $conditions = [];
        
        if(!empty($codigo_cidade)){   
            $conditions['codigo_endereco_cidade'] = $codigo_cidade;
        }

        if(!empty($q)){
           $conditions["descricao LIKE"] = "%{$q}%";
        }
        
        try {

            $data = $this->EnderecoBairro->find()
            ->where($conditions);
            
            $data->select($fields);

            if(empty($codigo_cidade) || empty($q)){
                $this->paginate($data);
            }

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter bairro de uma localidade
     * 
     * @param string $q Pesquisa por descrição de um bairro
     * @param string $codigo_cidade Pesquisa bairros de uma cidade
     * @return array
     */
    public function obterBairro($codigo){

        $data = [];
        $conditions = [];
        
        $codigo = (new FilterUtil)->toInt($codigo);

        $this->loadModel('EnderecoBairro');
        
        $fields = [];
       
        if(!empty($codigo)){
           $conditions["codigo"] = "{$codigo}";
        }
        
        try {

            $data = $this->EnderecoBairro->find()
            ->where($conditions);

            // $data->select($fields);

            $data->toArray();

        } catch (\Exception $e) {
            $this->responseError($e);
        }
        
        return $this->responseJson($data);
    }

    /**
     * Obter paises de varias localidades
     * 
     * @return array
     */
    public function obterPaises(){

        $data = [];

        $data = [
            'codigo' => 1,
            'abreviacao' => 'BR',
            'descricao' => 'Brasil'
        ];

        return $this->responseJson($data);
    }

}
