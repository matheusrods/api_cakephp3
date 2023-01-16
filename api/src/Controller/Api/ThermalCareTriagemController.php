<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
class ThermalCareTriagemController extends ThermalCareApiController
{

    // METODOS PUBLICOS

    /**
     * Obter triagem, listagem de medições
     * 
     *  GET api/thermal-care/triagem/medicoes/:codigo_usuario
     *  GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020
     *  GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020&mes=12
     *  GET api/thermal-care/triagem/medicoes/:codigo_usuario?ano=2020&mes=12&dia=15
     * 
     * @param int $codigo_usuario
     * @return json
     */
    public function obterListagemMedicoes($codigo_usuario = null){
        
        $data = [];
        
        $queryOp = $this->request->getQuery('op', self::$QUERY_EQ);


        $ano = $this->request->getQuery('ano', date('Y')); 
        $mes = $this->request->getQuery('mes', date('m')); 
        $dia = $this->request->getQuery('dia', date('d')); 

        $codigo_cliente_matriz = $this->obterCodigoClienteMatriz($codigo_usuario);
        
        // deve haver um codigo_cliente
        if(empty($codigo_cliente_matriz)){
            return $this->responseJson('Relacionamento não encontrado com usuário autenticado');
        } 

        $dh = []; // configurações para data e hora
        $dh['inicio'] = "{$ano}-{$mes}-{$dia} 00:00:00";
        $dh['fim'] = "{$ano}-{$mes}-{$dia} 23:59:59";

        $this->loadModel('ThermalTriagemMedicoes');
        $MedicoesData = $this->ThermalTriagemMedicoes->obterMedicoes( $codigo_cliente_matriz, $conditions = [], $dh, $queryOp);
        
        foreach ($MedicoesData->toArray() as $entity) {

            if(isset($entity->codigo)){
                
                $imagemData = $entity->imagem_medicao;

                $tmp['codigo'] = $entity->codigo;
                $tmp['cpf'] = $entity->cpf;
                $tmp['nome'] = $entity->nome;
                $tmp['temperatura_medida'] = $entity->temperatura_medida;
                $tmp['data_medicao'] = $entity->data_medicao;
                $tmp['imagem_medicao'] = $imagemData;
                $tmp['ativo'] = $entity->ativo;
                
                array_push( $data, $tmp );
            }
        }

        return $this->responseJson($data);
    }

    /**
     * Registrar medição de um funcionário
     * 
     * POST api/thermal-care/triagem/medicoes/:codigo_usuario
     * 
     * @param int $codigo_usuario
     * @return json
     */
    public function registrarMedicao($codigo_usuario = null){
        
        $data = [];

        $postData = $this->request->getData();
        // cpf do funcionario 
        $postDataCpf = isset($postData['cpf']) ? Comum::soNumero($postData['cpf']) : null;

        if(!Comum::validarCPF($postDataCpf)){
            return $this->responseJson('Cpf inválido');
        }

        if(!empty($postData['temperatura_medida']) && !is_numeric($postData['temperatura_medida'])){
            return $this->responseJson('Valor inválido de temperatura');
        }

        if(!is_numeric($postData['latitude']) || !is_numeric($postData['longitude'])){
            return $this->responseJson('Latitude e longitude devem ser numéricos');
        }


        // obter matriz do usuario logado
        $codigo_cliente_matriz = $this->obterCodigoClienteMatriz($codigo_usuario);

        if(empty($codigo_cliente_matriz)){
            return $this->responseJson('Relacionamento não encontrado com usuário autenticado');
        }

        // procurar funcionario com cpf fornecido
        $this->loadModel('ThermalFuncionarios');
        
        $FuncionarioData = $this->ThermalFuncionarios->obterFuncionarioPorCpf($postDataCpf, $codigo_cliente_matriz, false)->toArray();
        
        if(empty($FuncionarioData) || !isset($FuncionarioData[0])){
            return $this->responseJson('Colaborador não encontrado.');
        }

        $funcionario_nome = $FuncionarioData[0]['nome'];

        $saveData = array(
            'codigo_cliente' => $codigo_cliente_matriz,
            'cpf' => $postDataCpf,
            'nome' => $funcionario_nome,
            'temperatura_medida' => trim($postData['temperatura_medida']),
            'data_medicao' => $postData['data_medicao'],
            'latitude' => floatval($postData['latitude']),
            'longitude' => floatval($postData['longitude'])
        );
        
        $this->loadModel('ThermalTriagemMedicoes');
        $saveDataEntity = $this->ThermalTriagemMedicoes->newEntity($saveData);
        $saveDataEntity->set(['codigo_usuario_inclusao'=> 1]);
        $saveDataEntity->set(['ativo'=> 1]);
        $saveDataEntity->set(['data_inclusao'=> date("Y-m-d H:i:s")]);

        if (!$this->ThermalTriagemMedicoes->save($saveDataEntity)) {
            $data['error'] = $saveDataEntity->getValidationErrors();
        } else {
            $data = 'Registro incluído com sucesso';
        }

        return $this->responseJson($data);
    }

    /**
     * Parametros para determinar se apresenta alerta
     *
     * @param int $codigo_usuario
     * @return void
     */
    public function obterParametrosAlertas($codigo_usuario = null){

        $data = [];
        
        // quando alertar estado febril
        $this->loadModel('ThermalParametros');
        
        $data = $this->ThermalParametros->find()
            ->where(['ativo'=> 1])->toArray();

        return $this->responseJson($data);
    }

}