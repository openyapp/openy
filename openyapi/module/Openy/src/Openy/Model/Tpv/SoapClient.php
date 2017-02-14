<?php

namespace Openy\Model\Tpv;

use Openy\Traits\Openy\Log\LogTrait;
use Openy\Traits\XMLlintTrait;


class SoapClient extends \SoapClient
{

    use LogTrait;
    use XMLlintTrait;

    private $timeout;
    private $wsdl;
    private $location;

    public function __construct($logger = null, $wsdl, array $options = array()){
        $this->setLogger($logger);
        parent::__construct($wsdl, $options);
        if (array_key_exists('timeout', $options))
            $this->__setTimeout($options['timeout']);
        $this->wsdl = $wsdl;
    }

    public function __setTimeout($timeout)
    {
        if (!is_int($timeout) && !is_null($timeout))
        {
            throw new \Exception("Invalid timeout value");
        }

        $this->timeout = $timeout ? : null;
    }


    public function __soapCall ( $function_name , array $arguments , array $options = array(), array $input_headers=array(), array &$output_headers = null )
    {
        if (!isset($output_headers))
            $output_headers = [];

        $this->info('INFO: PERFOMING SOAP REQUEST CALL');

        $got_soap_fault = FALSE; // Tells whenever an error has been ocurred from soapCall or not
        $fault_response = null;
        try
        {

            $response = parent::__soapCall($function_name, $arguments, $options, $input_headers,$output_headers);
            if ($response instanceof \Exception) throw $response;
        }
        catch (\Exception $e)
        {
            $got_soap_fault = TRUE;
            $fault_response = $e;

            /**
             * @link  http://php.net/manual/en/function.is-soap-fault.php
             */
            if (is_soap_fault($e)){
                $this->debug('ERROR: SOAP ERROR ENCOUNTERED ',[
                                                                "faultcode"  =>(isset($e->faultcode)  ? $e->faultcode  : ""),
                                                                "faultactor" =>(isset($e->faultactor) ? $e->faultactor : "Server"),
                                                                "faultstring"=>(isset($e->faultstring)? $e->faultstring: $e->getMessage()),
                                                                "details"    =>(isset($e->detail)     ? $e->detail : "")]);
            }
            else{
                $this->debug('ERROR: SOAP ERROR ENCOUNTERED ',[
                                                                "exception"=>get_class($e),
                                                                "message"=>$e->getMessage()]);
            }
        }

        $this->info('INFO: FINISHED SOAP REQUEST CALL '.($got_soap_fault ? 'WITH ERRORS':''));
        if ($got_soap_fault)
            $this->debug('DETAILS: ',["location"        => array_key_exists('location', $options) ? $options['location'] : $this->location ,
                                      "request headers" => $this->__getLastRequestHeaders(),
                                      "request"         => $this->__getLastRequest(),
                                      "response headers"=> $this->__getLastResponseHeaders(),
                                      "response"        => isset($response)?(string)$response:""]);


        return ($fault_response ? : $response);
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {

        $this->location = $location;

        try{
            $xml = $this->XMLlint($request);
        }
        catch(\Exception $e){
            throw new \SoapFault($faultcode = "Client", $faultstring = "Malformed request body ",
                                  $faultactor = "Client" , $detail = ($e->getMessage() ? : $request) /* [, string $faultname [, string $headerfault ]]*/ );
            return null;
        }


        //$request = preg_replace('/ns1:trataPeticion/','ns1:BadFunction',$request);
        $no_response_received = FALSE;
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        $no_response_received = empty($response);

        if ($no_response_received && !$one_way)
        {
            $this->debug('ERROR: GOT EMPTY RESPONSE FROM SOAP REQUEST');
            $response = $this->__doCURLRequest($request,$location,$action,$version,$one_way);
        }

        if (!$one_way)
        {
            return ($response);
        }
    }


    protected function __doCURLRequest($request, $location, $action, $version, $one_way = 0)
    {
        $this->info('INFO: PERFOMING "RESCUE" SOAP cURL REQUEST');

        $curl = curl_init($location);

        curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml","SOAPAction: \"".$action."\""));
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        $response = curl_exec($curl);

        if (curl_errno($curl))
        {
            $this->debug('DEBUG: Got error when launching cURL',["error"=>curl_error($curl)]);
           //throw new \Exception(__CLASS__."::".__FUNCTION__.", error when curl_exec \n".curl_error($curl));
        }
        else{
            if ((!$one_way) && empty($response))
                $this->debug('ERROR: GOT EMPTY RESPONSE FROM "RESCUE" SOAP cURL REQUEST');
        }

        curl_close($curl);

        $this->info('INFO: FINISHED "RESCUE" SOAP cURL REQUEST');

        if(!$one_way)
            return $response;
    }



}