<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\ORM\TableRegistry;
use App\Utils\Comum;
use App\Utils\Tecnospeed;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Exception\Exception;
use Cake\Collection\Collection;
use App\Utils\ArrayUtil;
use Cake\Http\Client;
use Cake\Utility\Security as Security;
use DateInterval;
use DateTime;
use Cake\Log\Log;
use Cake\I18n\Time;

class MensageriaEsocialController extends ApiController
{

    
    public function initialize()
    {
        
        parent::initialize();

        //token para acesso
        $token = "de541d7b846e9580ed43597b74ade660a290bd658a6ffa4b2c0727945a87ded6";

        //verifica se tem token e se etá correto
        if(!is_null($this->request->getHeader('auth-token'))) {
            $header_token = (is_array($this->request->getHeader('auth-token'))) ? $this->request->getHeader('auth-token')[0] : $this->request->getHeader('auth-token');

            if($header_token == $token) {
                $this->Auth->allow('setEnviarCertificado');
            }
        }
        $this->connect = ConnectionManager::get('default');
    }

    /**
     * [set_enviar_certificado metodo post para enviar um certificado]
     */
    public function setEnviarCertificado()
    {
        
        $data = '';
        $error = array();
        if(!$this->request->allowMethod(['POST'])) {
            $error[] = "Erro ao processar o endpoint!";
        }

        $formData = $this->request->getData();

        //verificacoes
        if(empty($formData['codigo_cliente'])) {
            $error[] = "Necessário o valor para codigo cliente";
        }

        if(empty($formData['codigo_usuario'])) {
            $error[] = "Necessário o valor para codigo usuario";
        }

        if(empty($formData['codigo_certificado'])) {
            $error[] = "Necessário o valor para codigo certificado";
        }

        if(empty($formData['params'])) {
            $error[] = "Necessário os parametros do certificado";
        }

        //verifica se tem algum erro nos parametros enviados
        if(empty($error)) {
            //"/home/sistemas/rhhealth/c-care/c-care/app/tmp/certificados/79/certificado_23_140720211209.pfx","senha":"1234","cpfCnpjEmpregador":"20966646000135,20183726900000,20183726900200,20183726900300,20183726900100,20183726000114","email":"willians.pedroso@ithealth.com.br","razaoSocial":"RH HEALTH CONSULTORIA EM SAUDE & SAUDE OCUPACIONAL LTDA"}
            //trabalha o caminho passado            
            $arr_path = explode('/',$formData['params']['certificado']);
            $caminho_arquivo = "/home/sistemas/rhhealth/samba-share/arquivos/certificados/".$formData['codigo_cliente']."/".end($arr_path);
            
            $formData['params']['certificado'] = $caminho_arquivo;

            $tecno = new Tecnospeed();
            
            //modelo antigo de envio do certificado
            // $data = $tecno->tecnospeed_envia_certificado($formData['codigo_cliente'], $formData['codigo_usuario'], $formData['codigo_certificado'], $formData['params']);

            //metodo para cadastrar os empregadores | certificados e vincular eles
            //passado para fazer essa atividade 30-11-2022
            $data = $tecno->tecnospeed_cadastro_certificados($formData);

            
        }//fim tratamento dos parametros

        if(!empty($error)) {
            $this->set(compact('error'));
        }
        else {
            $this->set(compact('data'));
        }
    
    }//fim set_enviar_certificado



}
