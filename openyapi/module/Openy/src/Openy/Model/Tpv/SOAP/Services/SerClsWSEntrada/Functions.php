<?php

namespace Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada
{
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Functions\trataPeticion;
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types;

	/**
	 * Class cannot be abstract and final since PHP 7
	 * @see https://wiki.php.net/rfc/abstract_final_class PHP: RFC Static Classes
	 */
	final /*abstract*/ class Functions
	{
		protected static $trataPeticion;
		protected static $trataPeticionResponse;

		public static function trataPeticion($object= null,$new = true){
			$datoEntrada = Types::datoEntrada($object);
			if (is_null(self::$trataPeticion))
				self::$trataPeticion = new Functions\trataPeticion($datoEntrada);

			else if ($new)
				self::$trataPeticion->setDatoEntrada($datoEntrada);

			return self::$trataPeticion;
		}

		public static function trataPeticionResponse($object){
			$datoRetorno = Types::datoRetorno($object->trataPeticionReturn);
			if (is_null(self::$trataPeticionResponse))
				self::$trataPeticionResponse = new Functions\trataPeticionResponse($datoRetorno);

			else
				self::$trataPeticionResponse->setDatoRetorno($datoRetorno);

			return self::$trataPeticionResponse;
		}
	}
}

// Separated in two namespaces in order to facilitate future detachment
// or helping not depend the SOAP WebService implementation/requirements
namespace Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Functions
{
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types\datoEntrada;

	class trataPeticion
	{
		public $datoEntrada;

		public function __construct(datoEntrada $datoEntrada){
			$this->setDatoEntrada($datoEntrada);
		}

		public function setDatoEntrada(datoEntrada $datoEntrada){
			$this->datoEntrada = (string) $datoEntrada;
			$this->datoEntrada = trim($this->datoEntrada);
			return $this;
		}
	}
}

namespace Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Functions
{
	use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types\datoRetorno;

	class trataPeticionResponse
	{
		public $datoRetorno;

		public function __construct(datoRetorno $datoRetorno){
			$this->setDatoRetorno($datoRetorno);
		}

		public function getDatoRetorno(){
			return $this->datoRetorno;
		}

		public function setDatoRetorno(datoRetorno $datoRetorno){
			$this->datoRetorno = $datoRetorno;
		}
		}
	}