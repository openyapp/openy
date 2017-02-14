<?php

global $transactionPrefixFunction;

$transactionPrefixFunction = function ($type){
	switch($type):
		case 'creditcard' : return date('Y').'CRD';
		break;
		case 'order' : return date('Y').'OPYt';
		break;
		default: return '';
		break;
	endswitch;
};

defined('SOAP_1_1') or define('SOAP_1_1', 'SOAP_1_1');
defined('SOAP_1_2') or define('SOAP_1_2', 'SOAP_1_2');

$SOAP_1_1 = SOAP_1_1;
$SOAP_1_2 = SOAP_1_2;

$settings = <<<JSON
{
	"soap":{
		"dev_env": "remote",
		"prod_env": "remote",
		"environments":{
			"local":{
				"wsdl": {
					"url": "http:///soap/wsdl"
				},
				"url": "http:///soapi",
				"year": 2015,
				"version": "1.0",
				"functions": {
						"authorization" : "trataPeticion",
						"deferred_preauthorization" : "trataPeticion",
						"cancel_deferred_preauthorization" : "trataPeticion",
						"confirm_deferred_preauthorization": "trataPeticion",
						"refund_authorization": "trataPeticion",
						"autentication" : "trataPeticion"
				},
				"options":{
					"timeout": 2,
					"exceptions" : 1,
					"keep_alive" : false,
					"soap_version": {$SOAP_1_1},
					"trace": true,
					"encoding":"utf-8"
				}
			},
			"remote":{
				"wsdl": {
					"url": "https://sis-t.redsys.es:25443/sis/services/SerClsWSEntrada/wsdl/SerClsWSEntrada.wsdl"
				},
				"url": "https://sis-t.redsys.es:25443/sis/services/SerClsWSEntrada",
				"year": 2015,
				"version": "1.0",
				"functions": {
						"authorization" : "trataPeticion",
						"deferred_preauthorization" : "trataPeticion",
						"cancel_deferred_preauthorization" : "trataPeticion",
						"confirm_deferred_preauthorization": "trataPeticion",
						"refund_authorization": "trataPeticion",
						"autentication" : "trataPeticion"
				},
				"options":{
					"timeout": 2,
					"exceptions" : 1,
					"keep_alive" : false,
					"soap_version": {$SOAP_1_1},
					"trace": true,
					"encoding":"utf-8"
				}
			}
		}

	},
	"defaults": {
		"merchant_code": ,
		"terminal": 2,
		"pan" : panpan,
		"cvv": "123",
		"expiry" : "1712",
		"secret_since_dec_2015": "qwertyasdf0123456789",
		"secret": "secret",
		"currency":978,
		"merchant_group": null,
		"merchant_identifier": "REQUIRED"
	},
	"transaction":{
		"prefix":"transactionPrefixFunction"
	},
	"response":{
		"messages": {
			"default":{
				"text" : "Error en transacción bancaria",
				"translations":{
					"es_ES": "Error en transacción bancaria"
				}
			},
			"SIS0007":{
				"text" : "Error al desmontar XML de entrada",
				"translations":{
					"es_ES": "Error al desmontar XML de entrada"
				}
			},
			"SIS0019":{
				"text" : "Error de formato en Ds_Merchant_Amount",
				"translations":{
					"es_ES": "El TPV bancario rechazó la operación (SIS0019)"
				}
			},
			"SIS0042":{
				"text" : "Error en el cálculo del algoritmo HASH",
				"translations":{
					"es_ES": "El TPV bancario rechazó la operación (SIS0042)"
				}
			},
            "SIS0051":{
				"text" : "Número de pedido repetido",
				"translations":{
					"es_ES": "Número de pedido repetido (SIS0051)"
				}
			},
            "SIS0059":{
				"text" : "Error, no existe la operación sobre la que realizar la confirmación",
				"translations":{
					"es_ES": "Error en el proceso de pago del repostaje (SIS0059)"
				}
			},
			"SIS0070":{
				"text" : "Error en la caducidad de la tarjeta",
				"translations":{
					"es_ES": "Ocurrió un error en la validación de la tarjeta. Revise los datos (SIS0070)"
				}
			},
            "SIS0071":{
				"text" : "Tarjeta caducada",
				"translations":{
					"es_ES": "Tarjeta caducada (SIS0071)"
				}
			},
			"SIS0074":{
				"text" : "Falta el campo Ds_Merchant_Order",
				"translations":{
					"es_ES": "Ocurrió un error en el sistema de repostaje (SIS0074)"
				}
			},
			"SIS0216":{
				"text" : "El CVV2 tiene más de tres posiciones",
				"translations":{
					"es_ES": "Ocurrió un error en la validación de la tarjeta. Revise los datos (SIS0216)"
				}
			},
			"SIS0218":{
				"text" : "El comercio no permite realizar operaciones de pago seguros",
				"translations":{
					"es_ES": "Ocurrió un error de protocolo con el TPV bancario (SIS0218)"
				}
			},
			"SIS0270":{
				"text" : "Tipo de operación no activado para este comercio",
				"translations":{
					"es_ES": "Ocurrió un error de protocolo con el TPV bancario (SIS0270)"
				}
			},
			"SIS0298":{
				"text" : "El comercio no permite realizar operaciones de Pago con Referencia",
				"translations":{
					"es_ES": "Ocurrió un error de protocolo con el TPV bancario (SIS0298)"
				}
			},
			"SIS0321":{
				"text" : "La referencia indicada en Ds_Merchant_Identifier no está asociada al comercio",
				"translations":{
					"es_ES": "El TPV bancario rechazó la tarjeta (SIS0321)"
				}
			}
		}
	}
}
JSON;

$settings = json_decode($settings);
$settings = get_object_vars($settings);


return array(
		'tpv' => $settings
);
