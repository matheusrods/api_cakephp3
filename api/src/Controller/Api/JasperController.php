<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use SoapClient;
use SoapParam;


class SoapClientNG extends \SoapClient{

	//public function __doRequest($request, $location, $action, $version, $one_way = NULL){
    public function __doRequest($request, $location, $action, $version, $one_way = 0){
    	$result = parent::__doRequest($request, $location, $action, $version, $one_way);
		echo $result;
		exit;
    }
}

/**
 * Jasper Controller
 *
 *
 * @method \BtecApiJasper\Model\Entity\Jasper[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class JasperController extends ApiController
{
    private $url;
	private $username;
	private $password;

    public function initialize()
    {
        parent::initialize();
   		$this->url 		= Configure::read('jasper')['url'];
		$this->username = Configure::read('jasper')['username'];
		$this->password = Configure::read('jasper')['password'];
    }
	  
	public function requestReport() 
	{
		$parametros = $this->request->input('json_decode');

		debug($parametros);exit;

		$report = $parametros->report; 
		$format = $parametros->format; 
		$params = $parametros->params;
	    $params_xml = "";

	    foreach ($params as $name => $value) {
	      $params_xml .= "<parameter name=\"$name\"><![CDATA[".$value."]]></parameter>\n";
	    }

		$request = "
		  <request operationName=\"runReport\" locale=\"pt_BR\">
		           <argument name=\"USE_DIME_ATTACHMENTS\"><![CDATA[1]]></argument>
		           <argument name=\"RUN_OUTPUT_FORMAT\"><![CDATA[$format]]></argument>
		           <resourceDescriptor name=\"\" wsType=\"\" uriString=\"$report\" isNew=\"false\">
		               <label><![CDATA[null]]></label>
		               <parameter name=\"REPORT_LOCALE\"><![CDATA[pt_BR]]></parameter>
		               $params_xml
		           </resourceDescriptor>
		      </request>";
		
	    $client = new SoapClientNG(null, array(
	        'location'  => $this->url,
	        'uri'       => 'urn:',
	        'login'     => $this->username,
	        'password'  => $this->password,
	        'trace'    => 1,
	        'exception'=> 1,
	        'soap_version'  => SOAP_1_1,
	        'style'    => SOAP_RPC,
	        'use'      => SOAP_LITERAL,
	        'encoded'  => true,
	        //'cid'		=> 'report'
	    ));

	    $pdf = null;
	    try {
	      $result = $client->__soapCall('runReport', [
	        new SoapParam(trim($request),"requestXmlString") 
	      ]);
	      exit;
	    } catch(SoapFault $exception) {
	      $responseHeaders = $client->__getLastResponseHeaders();
	      if ($exception->faultstring == "looks like we got no XML document" &&
	          strpos($responseHeaders, "Content-Type: multipart/related;") !== false) {
	        $pdf = $this->parseReponseWithReportData($responseHeaders, $client->__getLastResponse());
	      } else {
	        throw $exception;
	      }
	    } 
	    if ($pdf)
	      return $pdf;
	    else
	      throw new \Exception("Jasper did not return PDF data. Instead got: \n$result");
	}
	  
  	protected function parseReponseWithReportData($responseHeaders, $responseBody) {
	    preg_match('/boundary="(.*?)"/', $responseHeaders, $matches);
	    $boundary = $matches[1];
	    $parts = explode($boundary, $responseBody);
	      
	    $pdf = null;
	    foreach($parts as $part) {
	      if (strpos($part, "Content-Type: application/pdf") !== false) {
	        $pdf = substr($part, strpos($part, '%PDF-'));
	        break;
	      }
	    }
	    
	    return $pdf;
	}
}
