<?php

namespace Openy\Model\Tpv;

//use \SoapClient;
use Zend\Stdlib\AbstractOptions;
use Openy\Interfaces\MapperInterface;

use Openy\Model\Tpv\SOAP\RequestEntity as Request;
use Openy\Model\Tpv\SOAP\ResponseEntity as Response;
//use Zend\Stdlib\Hydrator\ObjectProperty as RequestHydrator;
use Openy\Model\Hydrator\Tpv\SOAP\RequestHydrator;
use Openy\Model\Hydrator\Tpv\SOAP\ResponseHydrator;

use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Functions;

use Openy\Traits\Openy\Log\LogTrait;

class SoapMapper
	implements MapperInterface
{

	use LogTrait;

	protected $tpvoptions;


	public function __construct(AbstractOptions $tpvoptions){
		$this->tpvoptions = $tpvoptions;
		$this->getLogger();
	}



	public function request(Request &$request){
		$response = $this->insert($request);
		// TODO : Discuss commented block
		return $response;
	}

	/**
	 * Launch a request through SOAP WebService and awaits for getting response
	 * @param  Request $request The request to be send through WebService function
	 * @return Response         The response object for request sent
	 */
	public function insert($request){

		if (!($request instanceof Request))
			throw new \Exception(__CLASS__."::".__FUNCTION__.", expects first parameter to be a ".get_class(new Request()), 1);

		$RqtHydrator = new RequestHydrator($this->tpvoptions);
		$requestData = $RqtHydrator->extract($request);
		// Set request issuing timestamp (field sent)
		$request = $RqtHydrator->hydrate(array('sent'=>null),$request);
		$responseData = $this->soapCall($request,$requestData);
		if (($responseData instanceof \Exception) || is_soap_fault($responseData)){
			return $responseData;
		}
		else{
			$RspHydrator = new ResponseHydrator();
			$response = $RspHydrator->hydrate($responseData,new Response());
			//TODO : REMOVE THIS BLOCK CAUSE IS TEMPORARY HERE FOR TEST PURPOSES
			if (is_null($response->transactionid))
				$response->transactionid = $request->transactionid;
			//TODO : REMOVE PREVIOUS BLOCK CAUSE IS TEMPORARY HERE FOR TEST PURPOSES
	        $responseData['data'] = $requestData;
			$request = $RqtHydrator->hydrate($responseData,$request);

			return $response;
		}
	}


		protected function soapCall(Request $request, &$data){
			$trataPeticion = Functions::{$request->function}($data);
			$data = $trataPeticion->datoEntrada;

			$soapoptions = isset($this->tpvoptions->getSoap()['options']) ?
								$this->tpvoptions->getSoap('options')->toArray() :
								['exceptions'=>0,'connection_timeout'=>10, 'keep_alive'=>false];

			$client = new SoapClient($this->getLogger(),$request->wsdl,$soapoptions);
			try{
				$return = $client->__soapCall($request->function,[$trataPeticion]);
				//$return = $client->{$request->function}($trataPeticion);
			}catch(\Exception $e){
				if (!isset($return) || is_null($return))
					$return = $e;
			}
			if ($return instanceof \Exception){
				return $return;
			}
			else{
				try{
					$response = Functions::trataPeticionResponse($return);
					return $response->getDatoRetorno()->__toArray();
				}
				catch(\Exception $e){
					$this->debug('ERROR: RESPONSE RETURN NOT EXPECTED',["exeption"=>get_class($e),"message"=>$e->getMessage(),"response"=>$return]);
					return $e;
				}
			}

		}

		// TODO: Upcoming release will retrieve transactions from TPV SOAP SERVICE
		// https://sis-t.redsys.es:25443/apl-SOAP/rpcrouter
		// https://sis-t.redsys.es:25443/sis/wsdl/SerClsConsultasSIS.wsdl

	public function fetchAll($filters){

	}
	public function fetch($id,$where = []){}

	public function replace($id,$data){}
	public function update($id, $data){}
	public function delete($id){}
	public function locate($entity){}
	public function exists(&$entity, $fetch_entity_if_exists = false){}






}