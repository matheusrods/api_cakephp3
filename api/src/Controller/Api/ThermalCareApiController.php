<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use Cake\Validation\Validator;
use Cake\Log\Log;
use Cake\Core\Configure;

/**
 * Funções extendidas pertinentes ao Thermal Care
 * 
 */
class ThermalCareApiController extends ApiController
{
    /**
     * codigo_sistema padrão para Thermal Care
     * Thermal Care deve ser codigo_sistema = 3 na base de dados
     *
     * @var integer
     */
    public static $CODIGO_SISTEMA = 3; 

    public static $QUERY_EQ = 'eq'; // equal

    public static $QUERY_GT = 'gt'; // greater than

    public static $QUERY_LT = 'lt'; // lower than

    public static $QUERY_GTE = 'gte'; // greater than equal 

    public static $QUERY_LTE = 'lte'; // lower than equal
    
    public function initialize()
    {
        parent::initialize();
    
        $this->DATA_HORA_INICIO_PADRAO = $this->dataInicioFimPadrao()['inicio'];
        $this->DATE_HORA_FIM_PADRAO = $this->dataInicioFimPadrao()['fim'];    
    }

    /**
     * Retorna codigo_cliente da Matriz
     * Para usuários multi-cliente obter a matriz/grupo econômico a qual ele foi cadastrado.
     *
     * @param integer $codigo_usuario
     * @return int
     */
    public function obterCodigoClienteMatriz(int $codigo_usuario){

        $this->ThermalUsuario = $this->loadModel('ThermalUsuario');
        $codigo_cliente_matriz = $this->ThermalUsuario->obterCodigoMatrizPeloCodigoUsuario($codigo_usuario);
        return $codigo_cliente_matriz;

    }
    
    /**
     * Avalia se existe endereço de url para retornar uma imagem
     *
     * @param string $urlImagem
     * @return void
     */
    public function avaliaUrlImagem(string $urlImagem = null)
    {

        if(!empty($urlImagem)){
            $urlImagem = (strpos($urlImagem, 'https://api.rhhealth.com.br') !== false) ? $urlImagem : 'https://api.rhhealth.com.br' . $urlImagem;
        }

        return $urlImagem;
    }

    public function validateRequestQuery( $request, array $params )
    {

        $validator = new Validator();
        
        if(in_array('q', $params))
        {
            $validator
            ->requirePresence('q', true, 'Campo requerido')
            ->notEmptyString('q', 'Campo requerido')
            ->add('q', [
                'length' => [
                    'rule' => ['minLength', 2],
                    'message' => 'Parâmetro deve conter mais que 2 caracteres',
                ]
            ]);
        }

        if(!empty($validator->errors($request))){
            return $validator->errors($request);
        }

        return [];
    }

    public function dataInicioFimPadrao()
    {

        $dataHora = [];
        $dataHora['inicio'] = (new DatetimeUtil())->today();
        $dataHora['fim'] = (new DatetimeUtil())->now();

        return $dataHora;
    }
}


