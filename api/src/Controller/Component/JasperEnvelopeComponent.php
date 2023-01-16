<?php
// namespace BtecApiJasper\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use SoapClient;	

/**
 * JasperEnvelope component
 */
class JasperEnvelopeComponent extends Component
{
    //Properties
    private $wsdlURL;
    private $username;
    private $password;
    private $soapClient;
    private $reportName;
    private $outputFormat;
    private $language;
    private $parameterArray;
    
   //public function credenciais($wsdlURL, $username, $password) {
   public function __construct() {             
        
        $this->username = 'app_rhhealth';
        $this->password = 'buonny1818';
        
        try {
            if (Configure::read('ambiente') == 'SERVIDOR_PRODUCAO') {
              $this->wsdlURL = 'http://punto.local.buonny:8080/jasperserver/services/repository?wsdl';
            } else {
              $this->wsdlURL = 'http://gol.local.buonny:8090/jasperserver/services/repository?wsdl';
            }

            $this->soapClient = new soapClient($this->wsdlURL, array('login' => $this->username,'password' => $this->password,'trace' => 1));
        }
        catch (Exception $e) {
            throw $e;
        }
    }
   
    //Methods
    public function executeReport($reportName,  $parameterArray = "", $outputFormat = "PDF", $language = "pt_BR") {
        $this->reportName = $reportName;
        $this->outputFormat = $outputFormat;
        $this->language = $language;
        $this->parameterArray = $parameterArray;
        // debug($parameterArray);die;
        $requestXML  = "<request operationName=\"runReport\">";
        $requestXML .= "<argument name=\"RUN_OUTPUT_FORMAT\">$outputFormat</argument>";
        $requestXML .= "<resourceDescriptor name=\"\" wsType=\"reportUnit\" uriString=\"$reportName\" isNew=\"false\">";
        $requestXML .= "<label></label>";
        foreach ($parameterArray as $key => $value) {
            $requestXML .= "<parameter name=\"$key\"><![CDATA[$value]]></parameter>";
        }
        $requestXML .= "</resourceDescriptor></request>";
        
        $reportOutput = "";
        try {
            $response = $this->soapClient->runReport($requestXML);
            $reportOutput = $this->parseResponseWithReportData(
                $this->_soapClient->__getLastResponseHeaders(),
                $this->_soapClient->__getLastResponse(),
                $outputFormat
            );
        }//end of try
        catch (SoapFault $e) {
            if ($e->faultstring == 'looks like we got no XML document') {
                $reportOutput = $this->parseResponseWithReportData(
                    $this->soapClient->__getLastResponseHeaders(),
                    $this->soapClient->__getLastResponse(),
                    $outputFormat
                );
            }//end of if
            else {
                throw new Exception("Erro ao criar o relatorio: " . $e->faultstring);
            }//end of else
        }//end of catch
        return $reportOutput;
    }//end of function

    private function parseResponseWithReportData($responseHeaders, $response, $outputFormat) {
        preg_match('/boundary="(.*?)"/', $responseHeaders, $matches);
        $boundary = $matches[1];
        $parts = explode($boundary, $response);
        $reportOutput = "";
        switch ($outputFormat) {
            case 'HTML':
                foreach($parts as $part) {
                      if (strpos($part, "Content-Type: image/png") !== false) {
                          $start = strpos($part, "<") + 1;
                          $length = (strpos($part, ">") - $start);
                          $filename = substr($part, $start, $length) . '.png';
                          $file = fopen("$this->_imageFolder$filename","wb");
                          $contentStart = strpos($part, "PNG") - 1;
                          $contentLength = (strpos($part, "--") - $contentStart) + 1;
                          $contents = substr($part, $contentStart, $contentLength);
                          fwrite($file, $contents);
                          fclose($file);
                      }
                      if (strpos($part, "Content-Type: image/gif") !== false) {
                          $start = strpos($part, "<") + 1;
                          $length = (strpos($part, ">") - $start);
                          $filename = substr($part, $start, $length) . '.gif';
                          $file = fopen("$this->_imageFolder$filename","wb");
                          $contentStart = strpos($part, "GIF");
                          $contentLength = (strpos($part, "--") - $contentStart) + 1;
                          $contents = substr($part, $contentStart, $contentLength);
                          fwrite($file, $contents);
                          fclose($file);
                      }
                    if (strpos($part, "Content-Type: text/html") !== false) {
                        $contentStart = strpos($part, '<html>');
                        $contentLength = (strpos($part, '</html>') - $contentStart) + 7;
                        $reportOutput = substr($part, $contentStart, $contentLength);
                      }
                }//end of for each
                break;
            case 'PDF':
                foreach($parts as $part) {
                    if (strpos($part, "Content-Type: application/pdf") !== false) {
                        $reportOutput = substr($part, strpos($part, '%PDF-'));
                        break;
                      }
                } //end of foreach
                break;
            case 'CSV':
                foreach($parts as $part) {
                    if (strpos($part, "Content-Type: application/vnd.ms-excel") !== false) {
                        $contentStart = strpos($part, 'Content-Id: <report>') + 24;
                        $reportOutput = substr($part, $contentStart);
                        break;
                    }
                }
        }
        return $reportOutput;
    }//end of functoin
}
/*{
    private $soap;

    public function __construct()
    {
        $this->username = 'informacoes2';
        $this->password = 'buonny1818';
        try {
	        if(Configure::read('ambiente') ==  'SERVIDOR_PRODUCAO') {
              $this->wsdlURL = 'http://punto.local.buonny:8080/jasperserver/services/repository?wsdl';
            } else {
              // $this->wsdlURL = 'http://punto.local.buonny:8080/jasperserver/services/repository?wsdl';
              $this->wsdlURL = 'http://gol.local.buonny:8090/jasperserver/services/repository?wsdl';
            }

            $this->soap = new soapClient($this->wsdlURL, array('login' => $this->username,'password' => $this->password,'trace' => 1))
            ;
            
            // $this->soap->setOpt('timeout', -1);
            ini_set('default_socket_timeout', -1);
            set_time_limit(0);

        }
        catch (Exception $e) {
            throw $e;
        }
    }

    public function executarRelatorioDemonstrativoCt($nomeRelatorio, $parametros = null, $saida = 'PDF', $idioma = 'pt_BR')
    {
    	$parametros_xml;
        if(!is_null($parametros)){
            foreach($parametros as $chave => $valor){
                if(!isset($parametros_xml)){
                    $parametros_xml = '';
                }else{
                    $parametros_xml .= "\n";
                }
                $parametros_xml .= "<parameter name=\"$chave\"><![CDATA[$valor]]></parameter>";
            }
        }


        $saida = preg_replace('/\W/', '', $saida);
        $saida = ($saida ? $saida : 'PDF');

        $idioma = preg_replace('/\W/', '', $idioma);
        $idioma = ($idioma ? $idioma : 'pt_BR');

        $xml = "<request operationName=\"runReport\" locale=\"$idioma\">
                <argument name=\"USE_DIME_ATTACHMENTS\"><![CDATA[1]]></argument>
                    <argument name=\"RUN_OUTPUT_FORMAT\"><![CDATA[$saida]]></argument>
                    <resourceDescriptor name=\"\" wsType=\"\" uriString=\"$nomeRelatorio\" isNew=\"false\">
                        <label><![CDATA[null]]></label>
                        <parameter name=\"REPORT_LOCALE\"><![CDATA[$idioma]]></parameter>
                        $parametros_xml
                    </resourceDescriptor>
                </request>";   
        try {  
            $resultado = $this->soap->runReport($xml);   

            $attachments = $this->soap->_soap_transport->attachments['cid:report'];
            die;
            if (is_soap_fault($result)) {
                $errorMessage = $result->getFault()->faultstring;
                throw new Exception($errorMessage);
            }  
        debug('resultado'.$resultado);die;
    }*/


