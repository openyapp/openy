<?php

namespace Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada
{
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types\datoEntrada;
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types\datoRetorno;

	/**
	 * Class cannot be abstract and final since PHP 7
	 * @see https://wiki.php.net/rfc/abstract_final_class PHP: RFC Static Classes
	 */
	final /*abstract*/ class Types
	{

		protected static $datoEntrada;
		protected static $datoRetorno;

		/**
		 * [datoEntrada description]
		 * @return Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types\datoEntrada
		 */
		public static function datoEntrada($object=null){
			if (is_null(self::$datoEntrada))
				self::$datoEntrada = new datoEntrada($object);
			if (!is_null($object))
				self::$datoEntrada->__fromObject($object);
			return self::$datoEntrada;
		}

		public static function datoRetorno($retorno){
			if (is_null(self::$datoRetorno))
				self::$datoRetorno = new datoRetorno($retorno);
			else{
				if (is_string($retorno))
					self::$datoRetorno->__fromString($retorno);
				elseif (is_object($retorno))
					self::$datoRetorno->__fromObject($retorno);
			}
			return self::$datoRetorno;

		}

	}
}

namespace Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types
{

//COMMENT: We are importing RedsysAPIWs class
require_once('RedsysAPIWs.php');


	use Openy\Traits\XMLlintTrait;

	use \StdClass;

	use \RedsysAPIWs;

	class datoEntrada
	{

		use XMLlintTrait;

		public $merchant_code;
		public $currency;
		public $transaction_type;
		public $amount;
		public $terminal;
		public $version;
		public $order;
		public $pan;
		public $expiry;
		public $cvv;
		protected $secret;
		protected $signature;
		protected $merchant_group;
		protected $identifier = 'REQUIRED';

		public function __construct($datoEntrada){
			if (is_object($datoEntrada))
				$this->__fromObject($datoEntrada);
			elseif (is_array($datoEntrada))
				$this->__fromArray($datoEntrada);
			elseif (is_string($datoEntrada))
				$this->__fromString($datoEntrada);
		}

		public function setIdentifier($identifier){
			$this->identifier = (($identifier != 'REQUIRED') && ($identifier != '') ? $identifier : $this->identifier);
		}

		public function getIdentifier(){
			return ((strcmp($this->identifier,'REQUIRED') == 0) ? '' : $this->identifier);
		}

		public function __fromArray($array){
			$props = array_keys($this->__toArray());
			foreach($props as $prop){
				if (array_key_exists($prop,$array)){
					if (($prop == 'identifier') && is_null($array[$prop]))
						continue;
					else
						$this->{$prop} = $array[$prop];
				}
			}
			return $this;
		}

		public function __fromObject($object){
			return $this->__fromArray((array) $object);
		}

		public function __fromString($string){
			return $this->fromXML($string);
		}

		public function __toString(){
			return $this->toStringByTransactionType();
		}

		public function __toArray(){
			return get_object_vars($this);
		}

		protected function toStringByTransactionType(){
			switch ($this->transaction_type):
				case "0": // Autorización

				case "O": // Preautorización diferida

				case "P": // Confirmación de preautorización diferida

				case "7": // Autenticación

				case "3": // Devolución parcial

				default:
					return $this->toXML();

			endswitch;
		}

		public function from_xml($xml){
			return $this->fromXML($xml);
		}

		public function to_xml(){
			return $this->toXML();
		}

		protected function fromXML($xml){
			$xmldoc = $this->XMLlint($xml);
			$xmlobj = json_decode(\Zend\Json\Json::fromXml((string)$xml,true));

			if ($xmldoc && $xmldoc->count()){
				$DATOSENTRADA = $xmlobj->DATOSENTRADA;
				$arrDATOSENTRADA = (array)$DATOSENTRADA;
				extract($arrDATOSENTRADA);
				$this->version 			= $DS_VERSION;
				$this->currency 		= $DS_MERCHANT_CURRENCY;
				$this->transaction_type = $DS_MERCHANT_TRANSACTIONTYPE;
				$this->amount 			= $DS_MERCHANT_AMOUNT;
				$this->merchant_code 	= $DS_MERCHANT_MERCHANTCODE;
				$this->terminal 		= $DS_MERCHANT_TERMINAL;
				$this->order 			= $DS_MERCHANT_ORDER;
				if (isset($DS_MERCHANT_PAN)){
					$this->pan 			= $DS_MERCHANT_PAN;
					$this->expiry 		= $DS_MERCHANT_EXPIRYDATE;
					$this->cvv 			= $DS_MERCHANT_CVV2;
				}
				$this->identifier 	= $DS_MERCHANT_IDENTIFIER;
				$this->signature 	= $DS_MERCHANT_MERCHANTSIGNATURE;

			}
		}

		protected function toXML(){
			//$this->resetSignature();
			extract($this->__toArray());
			$output = <<<XML
<DATOSENTRADA>
<DS_VERSION>{$version}</DS_VERSION>
<DS_MERCHANT_CURRENCY>{$currency}</DS_MERCHANT_CURRENCY>
<DS_MERCHANT_TRANSACTIONTYPE>{$transaction_type}</DS_MERCHANT_TRANSACTIONTYPE>
<DS_MERCHANT_AMOUNT>{$amount}</DS_MERCHANT_AMOUNT>
<DS_MERCHANT_MERCHANTCODE>{$merchant_code}</DS_MERCHANT_MERCHANTCODE>
<DS_MERCHANT_TERMINAL>{$terminal}</DS_MERCHANT_TERMINAL>
<DS_MERCHANT_ORDER>{$order}</DS_MERCHANT_ORDER>
XML;
			if (!empty($pan) && !empty($expiry) && !empty($cvv)):
				$output .= <<<XML
<DS_MERCHANT_PAN>{$pan}</DS_MERCHANT_PAN>
<DS_MERCHANT_EXPIRYDATE>{$expiry}</DS_MERCHANT_EXPIRYDATE>
<DS_MERCHANT_CVV2>{$cvv}</DS_MERCHANT_CVV2>
XML;
			endif;
			$output .= "\n".<<<XML
<DS_MERCHANT_IDENTIFIER>{$identifier}</DS_MERCHANT_IDENTIFIER>
</DATOSENTRADA>
XML;

			$output = preg_replace("/^(\t|\s)+</m", '<', $output);
			$output = preg_replace("/>(\t|\s)+$/m", '>', $output);
			$output = preg_replace("/^(\t|\s)+$/m","", $output);
			$output = str_replace("\n",'',$output);


			$signature = $this->resetSignature($output);
			$output = <<<XML
				<REQUEST>
					{$output}
					<DS_SIGNATUREVERSION>HMAC_SHA256_V1</DS_SIGNATUREVERSION>
					<DS_SIGNATURE>{$signature}</DS_SIGNATURE>
				</REQUEST>
XML;
			return $output;
		}


		protected function resetSignature($output=null){
			if (is_null($output)):
				extract($this->__toArray());
				$chain = ltrim($amount,'0') . $order . $merchant_code . $currency .
						  ( (strcmp($identifier,'REQUIRED') == 0) ? $pan . $cvv : '') .
						  $transaction_type . $identifier . $secret;
				$this->signature = sha1($chain);
			else:
				//COMMENT: EOL removal necessary due new Requirements from provider
				//see Guia Migración a HMAC SHA265 at PROJECT_ROOT/docs/functional/tpv/reference
				$obj = new RedsysAPIWs();
				$this->signature = $obj->createMerchantSignatureHostToHost($this->secret,$output);
				return $this->signature;
			endif;

			return $this;
		}
	}

	class datoRetorno
	{

		use XMLlintTrait;

		public $retorno;
		protected $recibido;

		protected static $translations = [
							'Ds_Amount' 			=> 'amount',
							'Ds_Currency' 			=> 'currency',
							'Ds_Order' 				=> 'order',
							'Ds_Signature'  		=> 'signature',
							'Ds_MerchantCode' 		=> 'merchant_code',
							'Ds_Terminal'			=> 'terminal',
							'Ds_Response'			=> 'response',
							'Ds_Merchant_Identifier'=> 'identifier',
							'Ds_TransactionType' 	=> 'transaction_type',
		];

		public function __construct($retorno){
			$this->retorno = new StdClass();
			if (is_string($retorno))
				return $this->__fromString($retorno);
			elseif (is_object($retorno))
				return $this->__fromObject($retorno);
		}

		public function __fromString($retorno){
			return $this->fromXML($retorno);
		}
		public function __toString(){
			return $this->toStringByResponseType();
		}


		protected function toStringByResponseType(){
			switch ($this->retorno->response):
				case "0000":
				case "0900":
				break;
				default:
					// TODO: Tratamiento para el caso de error
					// en que los datos recibidos
				break;
			endswitch;
			return $this->toXML();
		}

		public function __toArray(){
			$result = get_object_vars($this->retorno);
			foreach($result as $key => $value):
				if (array_key_exists($key, self::$translations)):
					$result[self::$translations[$key]] = $value;
				endif;
			endforeach;
			return $result;
		}

		public function __fromObject($retorno){
			if ($retorno instanceof datoEntrada){
				$retorno = $retorno->__toArray();
				$this->recibido = (string)$retorno;
			}
			$retorno = (array)$retorno;
			return $this->__fromArray($retorno);
		}

		public function __fromArray($array){
			foreach($array as $key => $value):
				if (array_key_exists($key, self::$translations))
					$key = self::$translations[$key];
				if (!in_array($key,['pan','expiry','cvv','secret',]))
				$this->retorno->$key = $value;
			endforeach;
			return $this;
		}

		protected function toXML(){
			$array = $this->__toArray();
			extract($array);
			$output = <<<HEREDOC
			<RETORNOXML>
				<CODIGO>0</CODIGO>
				<Ds_Version>{$version}</Ds_Version>
				<OPERACION>
					<Ds_Amount>{$amount}</Ds_Amount>
					<Ds_Currency>{$currency}</Ds_Currency>
					<Ds_Order>{$order}</Ds_Order>
					<Ds_Signature>{$signature}</Ds_Signature>
					<Ds_MerchantCode>{$merchant_code}</Ds_MerchantCode>
					<Ds_Terminal>{$terminal}</Ds_Terminal>
					<Ds_Response>{$response}</Ds_Response>
					<Ds_AuthorisationCode>{$order}</Ds_AuthorisationCode>
					<Ds_TransactionType>{$transaction_type}</Ds_TransactionType>
					<Ds_SecurePayment>0</Ds_SecurePayment>
					<Ds_Language>1</Ds_Language>
					<Ds_Merchant_Identifier>{$identifier}</Ds_Merchant_Identifier>
					<Ds_MerchantData></Ds_MerchantData>
					<Ds_Card_Country>724</Ds_Card_Country>
				</OPERACION>
			</RETORNOXML>
HEREDOC;
			return $output;
		}

		protected function fromXML($xml){
			$xmldoc = $this->XMLlint($xml);
			$xmlobj = json_decode(\Zend\Json\Json::fromXml((string)$xml,true));

			if ($xmldoc && $xmldoc->count()){
				$codigo = (string)$xmldoc->xpath('CODIGO')[0];
				$recibido = count($xmldoc->xpath('RECIBIDO'));
				$operacion = count($xmldoc->xpath('OPERACION'));
				if ($operacion)
					$this->retorno = $xmlobj->RETORNOXML->OPERACION;
				else
					$this->retorno = $xmlobj->RETORNOXML->RECIBIDO->REQUEST->DATOSENTRADA;
				$this->retorno->codigo = $codigo;
				$this->retorno->data = $xmldoc->asXML();
			}
		}
	}
}




