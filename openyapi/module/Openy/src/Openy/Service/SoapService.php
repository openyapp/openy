<?php
/**
 * Service.
 * Openy POS SOAP Client Service
 *
 * @author XSubira <xsubira@openy.es>
 * @package Openy\Payment\POS\SOAP
 * @category SOAP
 * @see Openy\Module
 *
 */
namespace Openy\Service;

// SoapServiceInterface function arguments and returns
use Openy\Model\Transaction\TransactionEntity as Transaction;
use Openy\Model\Tpv\SOAP\RequestEntity as Request;
use Openy\Model\Tpv\SOAP\ResponseEntity as Response;
use Openy\Model\Classes\MessageEntity as Message;
// Constructor Arguments
use Openy\Interfaces\MapperInterface;
use Zend\Stdlib\AbstractOptions;

// Methods internal uses
use Openy\Model\Hydrator\Tpv\SOAP\RequestHydrator;

// Extends and Implements
use Openy\Interfaces\Properties\OptionsInterface;
use Openy\Traits\Properties\OptionsTrait;
use Openy\Interfaces\Properties\TpvOptionsInterface;
use Openy\Traits\Properties\TpvOptionsTrait;
use Openy\Interfaces\Service\SoapServiceInterface;


/**
 * SoapService.
 * Implements POS Soap Client Service Interface 
 *
 * @uses Openy\Interfaces\Service\SoapServiceInterface POS Soap Client Interface
 * @uses Openy\Model\Transaction\TransactionEntity POS Transaction Entity
 * @uses Openy\Model\Tpv\SOAP\RequestEntity SOAP Transaction Request Entity
 * @uses Openy\Model\Tpv\SOAP\ResponseEntity SOAP Transaction Response Entity
 * @uses Openy\Model\Classes\MessageEntity Transaction Status Message Entity 
 *
 * @uses Openy\Interfaces\Properties\OptionsInterface Openy Options Property Interface
 * @uses Openy\Traits\Properties\OptionsTrait Openy Options Property Trait
 * @uses Openy\Interfaces\Properties\TpvOptionsInterface Openy POS (TPV) Options Property Interface
 * @uses Openy\Traits\Properties\TpvOptionsTrait Openy POS (TPV) Options Property Trait
 * 
 * 
 */
class SoapService
	implements 	SoapServiceInterface,
				OptionsInterface,
				TpvOptionsInterface				
{

	use OptionsTrait,
		TpvOptionsTrait;
	
	/**
	 * Mapper for handling Soap Client calls
	 * @var MapperInterface
	 * @see \Openy\Model\Tpv\SoapMapper SOAP Client Mapper Class
	 */
	protected $soapMapper;
	
	/**
	 * Mapper for handling persisted Requests. 
	 * @var MapperInterface 
	 * @see \Openy\Model\Tpv\SOAP\RequestMapper SOAP Request Mapper Class
	 */
	protected $requestMapper;

	/**
	 * Mapper for handling persisted Responses to Requests.
	 * @var MapperInterface
	 * @see \Openy\Model\Tpv\SOAP\ResponseMapper SOAP Response Mapper Class
	 */	
	protected $responseMapper;

	/**
	 * Last Response to an issued Request.
	 * @var \unknown
	 */
	protected $lastresponse;

	/**
	 * Constructor.
	 * @param MapperInterface $requestMapper
	 * @param MapperInterface $responseMapper
	 * @param MapperInterface $soapMapper
	 * @param AbstractOptions $options
	 * @param AbstractOptions $tpvoptions
	 */
	public function __construct(
		MapperInterface $requestMapper,
		MapperInterface $responseMapper,
		MapperInterface $soapMapper,
		AbstractOptions $options,
		AbstractOptions $tpvoptions)
	{
		$this->setOptions($options);
		$this->setTpvoptions($tpvoptions);
		$this->soapMapper = $soapMapper;
		$this->requestMapper = $requestMapper;
		$this->responseMapper = $responseMapper;		
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\SoapServiceInterface
	 */
	public function getRequest(Transaction $transaction){
		$request = $this->getRequestFromTransaction($transaction);
		$located = $this->requestMapper->locate($request);
		$exception = false;
		// Locates a request for the given transaction
		//if ((bool)$located->idsoaprequest):
		if (((bool)$located->idsoaprequest) && ($located->transactionType == $request->transactionType)):
			return $located;

		else:
		//TODO: Discuss calls definition, e.g.
		//      if must have output params or a getResponseInstance is recommended instead of catching request() return
			// Requires a new SOAP Request creation
			$response = $this->soapMapper->insert($request);
			// ERROR CONTROL
			if ($response instanceof \Exception){
				$exception = $response;
				$response = new Response();
				$data = ["Error" => get_class($exception),"Message"=>$exception->getMessage()];
				if ($exception instanceof \SoapFault){
					$data = ["faultcode"  =>(isset($exception->faultcode)  ? $exception->faultcode  : ""),
                             "faultactor" =>(isset($exception->faultactor) ? $exception->faultactor : "Server"),
                             "faultstring"=>(isset($exception->faultstring)? $exception->faultstring: $exception->getMessage()),
                             "details"    =>(isset($exception->detail)     ? $exception->detail : "")];

				}
				$response->data = json_encode($data);
			}
			// Persists the SOAP call Request and Response objects in repository
			// A) Request
			$request = $this->requestMapper->insert($request);
			// B) Response
			$response->idsoaprequest = $request->idsoaprequest;
			$response->transactionid = $request->transactionid;
			$response= $this->responseMapper->insert($response);
		endif;
		return $request;
	}
	
		protected function getRequestFromTransaction(Transaction $transaction){
			$hydrator = new RequestHydrator($this->getTpvOptions());
        	$data = $hydrator->extract($transaction);
			$request = $hydrator->hydrate($data,new Request);

			if ($this->tpvoptions){
				$request->data = ''; //TODO: In the upcoming future
				$request->url = $this->getTpvOptions()->getSoap()->getUrl();
				$request->wsdl = $this->getTpvOptions()->getSoap()->getWsdl()->getUrl();
				$request->version = $this->getTpvOptions()->getSoap()->getVersion();
				$request->currency = $this->getTpvOptions()->getDefaults()->getCurrency();

				switch($transaction->transactionType):
					case Transaction::TRANSACTION_TYPE_AUTH:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['authorization'];
						break;
					case Transaction::TRANSACTION_TYPE_PREAUTH:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['deferred_preauthorization'];
						break;
					case Transaction::TRANSACTION_TYPE_CANCEL_PREAUTH:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['cancel_deferred_preauthorization'];
						break;
					case Transaction::TRANSACTION_TYPE_CONFIRM_PREAUTH:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['confirm_deferred_preauthorization'];
						break;
					case Transaction::TRANSACTION_TYPE_REFUND_AUTH:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['refund_authorization'];
						break;
					case Transaction::TRANSACTION_TYPE_AUTENTICATE:
					default:
							$request->function = $this->getTpvOptions()->getSoap()->getFunctions()['autentication'];
						break;
				endswitch;

			}
			return $request;
		}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\SoapServiceInterface
	 */
	public function getResponse(Request $request){
		$response = $this->responseMapper->fetch($request->idsoaprequest);
		$response->authorizationcode = $response->authorizationcode ? : $request->authorizationcode;
		// Information not persisted in database
		$response->response = $request->lastresponse;
		//$response->token = $request->token;
		$response->secret = $request->secret;

		return $response;
	}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\SoapServiceInterface
	 */	
	public function hasValidResponse(Request $request){
		if (isset($request->lastresponse) && !is_null($request->lastresponse))
			return $this->isValidDsResponse($request->lastresponse);
		else{
			$response = $this->getResponse($request);
			if ((bool)$response->idsoaprequest):
				if (
				    !preg_match(Response::RESPONSE_TYPE_ERROR_PREG,$response->code)
					&&
					(Response::RESPONSE_TYPE_OK == $response->code)
					):
					return $this->isValidDsResponse($response->response);
				endif;
			endif;
		}
		return false;
	}

		protected function isValidDsResponse($response){

			$this->lastresponse = $response;
			$response_matched_errors = array_filter(Response::DS_RESPONSES_TYPE_KO,array($this,'DsResponseErrorMatch'));
			$this->lastresponse = null;

			//TODO: Dar un tratamiento al tipo de respuesta en función del tipo de petición
			//$response->response w/ $request->transactionType;
			//		$request = $request ? : $this->requestMapper->fetch($response->idsoaprequest);

			$result = (count($response_matched_errors) == 0);
			return $result;
		}

			protected function DsResponseErrorMatch($error){
				return preg_match('/^0*+'.$error.'$/',$this->lastresponse);
			}

	/**
	 * {@inheritDoc}
	 * @see \Openy\Interfaces\Service\SoapServiceInterface
	 */					
	public function getTransactionStatus(Transaction $transaction){
		$response_code = empty($transaction->lastcode) ? $transaction->lastresponse : $transaction->lastcode;
		$messageOptions = $this->getTpvOptions()->getResponse()->getMessages();
		$messageOptions = isset($messageOptions[$response_code]) ? $messageOptions[$response_code] : $messageOptions['default'];
		$message = new Message($messageOptions,$response_code);
		return $message;
	}

}
