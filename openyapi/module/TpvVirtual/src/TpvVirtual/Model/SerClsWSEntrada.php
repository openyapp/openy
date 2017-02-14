<?php

namespace TpvVirtual\Model;

use Openy\Model\Tpv\SOAP\Services\SerClsWSEntrada\Types;

class SerClsWSEntrada {
    /**
     * This method takes ...
     *
     * @param integer $inputParam
     * @return string
     */
    public static function trataPeticion($datoEntrada) {
        $transaction = Types::datoEntrada($datoEntrada);

        switch($transaction->transaction_type):
            case "O":
                $retorno = self::trataPreautorizacion($transaction);
                break;
            case "P":
                $retorno = self::trataConfirmacion($transaction);
                break;
            case "3":
                $retorno = self::trataDevolucionParcial($transaction);
                break;
            default:
                $retorno = Types::datoRetorno($transaction);
                break;
        endswitch;
        return new \SoapParam((string)$retorno,'trataPeticionReturn');
    }

    protected static function trataPreautorizacion(datoEntrada $transaction){
        if ($transaction->getIdentifier() == FALSE):
            extract($transaction);
            $chain = $merchant_code . $pan . $cvv . $expiry . $order; //order añade aliatoriedad
            $transaction->setIdentifier(sha1($chain));
        endif;
        $retorno = Types::datoRetorno($transaction);
        $retorno->retorno->response = '0000';
        return $retorno;
    }

    protected static function trataConfirmacion($datosEntrada){
        // Place here additional checks and properties inits
        $retorno = Types::datoRetorno($transaction);
        $retorno->retorno->response = '0900';
        return $retorno;
    }

    protected static function trataDevolucionParcial($datosEntrada){
        // Place here additional checks and properties inits
        $retorno = Types::datoRetorno($transaction);
        $retorno->retorno->response = '9000';
        return $retorno;
    }

}