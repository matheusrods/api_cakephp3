<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * Profissional Controller
 *
 * @property \App\Model\Table\ProfissionalTable $Profissional
 *
 * @method \App\Model\Entity\Profissional[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfissionalController extends ApiController
{
    /**
     * Obter profissionais
     * [ /api/profissionais/:codigo_cliente]
     * 
     * @param integer $codigo_usuario
     * @return json
     */
    public function obterProfissionais( int $codigo_usuario = null ){

        // opcional
        $incluir_fornecedores = false; // incluir fornecedores nesta consulta
        
        // inicializando variaveis
        $data = [];             // dados de retorno
        $query_params = [];     // parametros de busca/pesquisa
        $query_options = ['incluir_fornecedores' => $incluir_fornecedores]; // opcoes de busca/pesquisa
        $hasParams = false;     // flag especifica se há parametros de pesquisa

        $this->loadModel('Usuario');
        $codigo_cliente = $this->Usuario->obterClientePorCodigoUsuario($codigo_usuario);

        if(isset($codigo_cliente['error'])){
            $data['error'] = $codigo_cliente['error'];
            return $this->responseJson($data);
        }

        if(!empty($this->request->query('nome'))){
            $hasParams = true;
            $query_params['nome'] = $this->request->query('nome');
        }

        if(!empty($this->request->query('uf'))){
            $hasParams = true;
            $query_params['conselho_uf'] = $this->request->query('uf');
        }

        if(!empty($this->request->query('conselho'))){
            $hasParams = true;
            $query_params['numero_conselho'] = $this->request->query('conselho');
        }
        if(!empty($this->request->query('crm'))){
            $hasParams = true;
            $query_params['crm'] = $this->request->query('crm');
        }
        if(!empty($this->request->query('cro'))){
            $hasParams = true;
            $query_params['cro'] = $this->request->query('cro');
        }

        if(!empty($this->request->query('search'))){
            $hasParams = true;
            $query_params['search'] = $this->request->query('search');
        }

        if(!$hasParams){
            $data['error'] = "Nenhum parâmetro informado. (nome, uf, conselho, crm, cro)";
            return $this->responseJson($data);
        }

        $data = $this->Profissional->pesquisaProfissional( $query_params,  );
    
        return $this->responseJson($data);

    }
}