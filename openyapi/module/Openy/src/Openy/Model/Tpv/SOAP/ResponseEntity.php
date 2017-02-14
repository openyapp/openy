<?php

namespace Openy\Model\Tpv\SOAP;

class ResponseEntity
{

	const RESPONSE_TYPE_OK = '0';
	const RESPONSE_TYPE_ERROR_PREG = '/SIS[0-9]{4}/';

	const DS_RESPONSES_TYPE_KO = [  // B) CODIGOS PARA TRANSACCIONES DENEGADAS
									// b.1.) Transacciones denegadas por motivos genéricos
									'101','102','104','106','107','109','110',
									'114','116','118','119','125','129','167',
									'180','181','182','184','190','191',
									// b.2.) Transacciones denegadas por motivos en los que el banco emisor de la tarjeta considera que existen indicios de fraude.
									'201','202','204','207','208','209','280','290',
									// C) CODIGOS REFERIDOS A ANULACIONES O DEVOLUCIONES
									//'400', //ANULACION ACEPTADA
									'480',
									//'481', //ANULACION ACEPTADA
									// D) CODIGOS REFERIDOS A CONCILIACIONES DE PRE-AUTORIZACIONES O PRE-AUTENTICACIONES (Ds_Merchant_TransactionType = 2, 8, O o R)
									//'500', //CONCILIACION ACEPTADA
									'501','502','503',
		//TODO :  DAR UN TRATAMIENTO A LOS SIGUIENTES ESTADOS DE PREAUTORIZACIONES
		//		  EN FUNCIÓN DE LA OPERACIÓN QUE HAYAMOS SOLICITADO
									//'9928', // ANULACIÓN DE PREAUTORITZACIÓN REALIZADA POR EL SISTEMA
									//'9929', // ANULACIÓN DE PREAUTORITZACIÓN REALIZADA POR EL COMERCIO
									// E) CODIGOS DE ERROR ENVIADOS POR LA PROPIA PLATAFORMA DE PAGOS DE BANCO SABADELL
									'904','909','912','913','916','928','940',
									'941','942','943','944','945','946','947',
									'949','950','965','9064','9078','9093','9094',
									'9104','9142','9218','9253','9256','9261',
									'9281','9283','9912','9913','9914','9915',
									//'9928', //PREAUTORIZACIÓN EN DIFERIDO ANULADA
									//'9997'  //PREAUTORIZACIÓN EN DIFERIDO ANULADA
									//'9929', // PREAUTORIZACIÓN EN DIFERIDO ANULADA
									'9997',
		//TODO :  DAR UN TRATAMIENTO A LOS SIGUIENTES ESTADOS TEMPORALES
									'9998', // ESTADO OPERACIÓN: SOLICITADA
									'9999', // ESTADO OPERACIÓN: AUTENTICANDO
								 ];

	public $idsoaprequest;
	public $received;
	public $data;
	public $transactionid;
	public $response;
	public $code;
	// NON DB FIELDS
	public $authorizationcode;
	public $token;
}