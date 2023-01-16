<?php
// namespace BtecApiJasper\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * JasperClient component
 */
class JasperClientComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    private $url;
	private $username;
	private $password;

	public function __construct($url, $username, $password) {
		$this->url = $url;
		$this->username = $username;
		$this->password = $password;
	}
	  
	public function requestReport($report, $format, $params) {
	    $params_xml = "";
	    foreach ($params as $name => $value) {
	      $params_xml .= "<parameter name=\"$name\"><![CDATA[$value]]></parameter>\n";
	    }
	    
	    $request = "
	      <request operationName=\"runReport\" locale=\"en\">
	        <argument name=\"RUN_OUTPUT_FORMAT\">$format</argument>
	        <resourceDescriptor name=\"\" wsType=\"\"
	        uriString=\"$report\"
	        isNew=\"false\">
	        <label>null</label>
	        $params_xml
	        </resourceDescriptor>
	      </request>
	    ";
	    
	    $client = new SoapClient(null, array(
	        'location'  => $this->url,
	        'uri'       => 'urn:',
	        'login'     => $this->username,
	        'password'  => $this->password,
	        'trace'    => 1,
	        'exception'=> 1,
	        'soap_version'  => SOAP_1_1,
	        'style'    => SOAP_RPC,
	        'use'      => SOAP_LITERAL

	      ));

	    $pdf = null;
	    try {
	      $result = $client->__soapCall('runReport', array(
	        new SoapParam($request,"requestXmlString") 
	      ));
	      
	      $pdf = $this->parseReponseWithReportData(
	        $client->__getLastResponseHeaders(),
	        $client->__getLastResponse());
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
	      throw new Exception("Jasper did not return PDF data. Instead got: \n$result");
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

