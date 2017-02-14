<?php

namespace Opypos\Adapter;

use Zend\Stdlib\AbstractOptions;

class aadapterAdapter extends AbstractOptions
{
    public function exchangeConfigArray($entity, $data)
    {
        //$entity->idconfig     = (isset($data[0]['IDCONFIG']))        ? $data[0]['IDCONFIG']         : null;
        $entity->idopystation       = (isset($data[0]['idopystation']))         ? $data[0]['idopystation']          : null;
        $entity->idoffstation       = (isset($data[0]['idoffstation']))         ? $data[0]['idoffstation']          : null;
        $entity->currency           = (isset($data[0]['moneda']))               ? $data[0]['moneda']                : null;
        $entity->units              = (isset($data[0]['unidades']))             ? $data[0]['unidades']              : null;
        $entity->pos_name           = (isset($data[0]['pos_nombre']))           ? $data[0]['pos_nombre']            : null;
        $entity->pos_email          = (isset($data[0]['pos_email']))            ? $data[0]['pos_email']             : null;
        $entity->pos_website        = (isset($data[0]['pos_paginaweb']))        ? $data[0]['pos_paginaweb']         : null;
        $entity->pos_phone          = (isset($data[0]['pos_telefono']))         ? $data[0]['pos_telefono']          : null;
        $entity->pos_address        = (isset($data[0]['pos_direccion']))        ? $data[0]['pos_direccion']         : null;
        $entity->pos_locality       = (isset($data[0]['pos_localidad']))        ? $data[0]['pos_localidad']         : null;
        $entity->pos_province       = (isset($data[0]['pos_provincia']))        ? $data[0]['pos_provincia']         : null;
        $entity->pos_postalcode     = (isset($data[0]['pos_codigopostal']))     ? $data[0]['pos_codigopostal']      : null;
        $entity->pos_country        = (isset($data[0]['pos_pais']))             ? $data[0]['pos_pais']              : null;
        $entity->inv_name           = (isset($data[0]['inv_nombre']))           ? $data[0]['inv_nombre']            : null;
        $entity->inv_address        = (isset($data[0]['inv_direccion']))        ? $data[0]['inv_direccion']         : null;
        $entity->inv_locality       = (isset($data[0]['inv_localidad']))        ? $data[0]['inv_localidad']         : null;
        $entity->inv_province       = (isset($data[0]['inv_provincia']))        ? $data[0]['inv_provincia']         : null;
        $entity->inv_country        = (isset($data[0]['inv_pais']))             ? $data[0]['inv_pais']              : null;
        $entity->inv_postalcode     = (isset($data[0]['inv_codigo_postal']))    ? $data[0]['inv_codigo_postal']     : null;
        $entity->inv_document_type  = (isset($data[0]['inv_tipo_documento']))   ? $data[0]['inv_tipo_documento']    : null;
        $entity->inv_document       = (isset($data[0]['inv_documento']))        ? $data[0]['inv_documento']         : null;
        $entity->openy_payment_type = (isset($data[0]['openy_tipo_pago']))      ? $data[0]['openy_tipo_pago']       : null;
        $entity->openy_terminal     = (isset($data[0]['openy_terminal']))       ? $data[0]['openy_terminal']        : null;
        $entity->openy_user_id      = (isset($data[0]['openy_user_id']))        ? $data[0]['openy_user_id']         : null;
        $entity->product_conversor  = (isset($data[0]['product_conversor']))    ? $data[0]['product_conversor']     : null;

        return $entity;
    }

    public function exchangeClientArray($entity, $data)
    {

        $entity->posIdClient        = (isset($data['IDCLIENTE']))           ? $data['IDCLIENTE']   : null;
        $entity->posClientCode      = (isset($data['CODIGOCLEINTE']))       ? $data['CODIGOCLEINTE'] : null;
        $entity->posName            = (isset($data['NOMBRE']))              ? $data['NOMBRE']      : null;
        $entity->posNif             = (isset($data['NIF']))                 ? $data['NIF']         : null;
        $entity->posAddress         = (isset($data['DIRECCION']))           ? $data['DIRECCION']   : null;
        $entity->posPostalCode      = (isset($data['CP']))                  ? $data['CP']   : null;
        $entity->posLocality        = (isset($data['POBLACION']))           ? $data['POBLACION']   : null;
        $entity->posProvince        = (isset($data['PROVINCIA']))           ? $data['PROVINCIA']   : null;
        $entity->posIdComunity      = (isset($data['IDCOMUNIDAD']))         ? $data['IDCOMUNIDAD']   : null;
        $entity->posCounttry        = (isset($data['PAIS']))                ? $data['PAIS']   : null;
        $entity->posPhone           = (isset($data['TELEFONO1']))           ? $data['TELEFONO1']   : null;
        $entity->posEmail           = (isset($data['EMAIL']))               ? $data['EMAIL']   : null;
        $entity->posSignDate        = (isset($data['FECHAALTA']))           ? $data['FECHAALTA']   : null;
        $entity->posRisk            = (isset($data['RIESGO']))              ? $data['RIESGO']   : null;
        $entity->posCredit          = (isset($data['CREDITO']))             ? $data['CREDITO']   : null;
        $entity->posLastBillDate    = (isset($data['FECHAULTIMACOMPRA']))   ? $data['FECHAULTIMACOMPRA']   : null;
        $entity->posInvAddress      = (isset($data['DIRECCIONFACT']))       ? $data['DIRECCIONFACT']   : null;
        $entity->posInvPostalCode   = (isset($data['CPFACT']))              ? $data['CPFACT']   : null;
        $entity->posInvLocality     = (isset($data['POBLACIONFACT']))       ? $data['POBLACIONFACT']   : null;
        $entity->posInvProvince     = (isset($data['PROVINCIAFACT']))       ? $data['PROVINCIAFACT']   : null;
        $entity->posInvCountry      = (isset($data['PAISFACT']))            ? $data['PAISFACT']   : null;



        //             $entity->idopystation = (isset($data['idopystation']))    ? $data['idopystation']     : null;

        return $entity;
    }

    public function exchangePriceArray($entity, $data)
    {
            $entity->opyProductType    = (isset($data['opyProductType']))       ? $data['opyProductType']        : null;
            $entity->posProductCode       = (isset($data['CODIGOPRODUCTO']))       ? $data['CODIGOPRODUCTO']        : null;
            $entity->posConcept           = (isset($data['CONCEPTO']))             ? $data['CONCEPTO']              : null;
            $entity->posDescription       = (isset($data['DESCRIPCION']))          ? $data['DESCRIPCION']           : null;
            $entity->posPrice             = (isset($data['PRECIOVENTA']))          ? $data['PRECIOVENTA']           : null;
            $entity->promotionPerValue    = (isset($data['PROMOCIONES']))          ? $this->getPromotionsArray($data['PROMOCIONES'])   : null;

//             $entity->idopystation = (isset($data['idopystation']))    ? $data['idopystation']     : null;

        return $entity;
    }

    public function exchangePromotionsArray($promotion)
    {
        $posPromotion = array();
//         IDPROMOCION": "18",
//                         "UNIDADES": 0,
//                         "PRECIOUNIDAD": "1.089000000",
//                         "IMPORTE": 0,
//                         "DESCUENTOXUNIDAD": 0.03,
//                         "DESCUENTOPORC": 0,
//                         "PRECIOXUNIDAD": 1.059,
//                         "PROMO_PRECIOXPORC": 0,
//                         "PROMO_PRECIOXUNIDAD": 0,
//                         "DESCUENTO": 0,
//                         "IMPORTEFINAL": 0



        $porPromotion['idPromotion'] = $promotion['IDPROMOCION'];
        $porPromotion['units'] = $promotion['UNIDADES'];
        $porPromotion['originalPricePerUnit'] = $promotion['PRECIOUNIDAD'];
        $porPromotion['value'] = $promotion['IMPORTE'];
        $porPromotion['discountPerUnit'] = $promotion['DESCUENTOXUNIDAD'];
        $porPromotion['discountPercentage'] = $promotion['DESCUENTOPORC'];
        $porPromotion['pricePerUnit'] = $promotion['PRECIOXUNIDAD'];
        $porPromotion['promPricePorcentage'] = $promotion['PROMO_PRECIOXPORC'];
        $porPromotion['promPricePerIUnit'] = $promotion['PROMO_PRECIOXUNIDAD'];
        $porPromotion['promoUnits'] = $promotion['PROMO_UNIDADES'];
        $porPromotion['promType'] = ($promotion['PROMOTYPE']=='PROMOXUNIDAD')?'perUnit':'discount';
        $porPromotion['discount'] = $promotion['DESCUENTO'];
        $porPromotion['priceToPay'] = $promotion['IMPORTEFINAL'];


        return $porPromotion;
    }

    public function getPromotionsArray($promotions)
    {
        $posPromotion=array();
        foreach ($promotions as $key => $promotion)
        {
            $posPromotion[$key] = $this->exchangePromotionsArray($promotion);
        }

        return $posPromotion;
    }

    public function exchangePumpArray($entity, $data)
    {
        $entity->idpump             = (isset($data['IDSURTIDOR']))       ? $data['IDSURTIDOR']        : null;
        $entity->price              = (isset($data['PRECIOVENTA']))       ? $data['PRECIOVENTA']        : null;
        $entity->opyIdProductType   = (isset($data['opyProductType']))       ? $data['opyProductType']        : null;
        $entity->posLabel              = (isset($data['CONCEPTO']))       ? $data['CONCEPTO']        : null;
        $entity->posDescription        = (isset($data['DESCRIP']))       ? $data['DESCRIP']        : null;
        $entity->posIdIsland           = (isset($data['IDISLA']))       ? $data['IDISLA']        : null;
        $entity->posHosepipeNumber     = (isset($data['NMANGUERA']))       ? $data['NMANGUERA']        : null;
        $entity->posIdProductType   = (isset($data['IDPRODUCTO']))       ? $data['IDPRODUCTO']        : null;
        $entity->posIdTank             = (isset($data['IDTANQUE']))       ? $data['IDTANQUE']        : null;
        $entity->posPumpStatus      = (isset($data['status']))       ? $data['status']        : null;
        $entity->posBinaryStatus      = (isset($data['status']))       ? base_convert($data['status'], 16, 2)        : null;
        $entity->opySatus             = (isset($data['status']))       ? $this->getStatus($data['status'])        : null;



//         $bstatus = $this->getStatu($data['status']);
        //$entity->idopystation = (isset($data['idopystation']))    ? $data['idopystation']     : null;

        return $entity;
    }

    public function exchangeCollectArray($data)
    {
        $result = array();

        $result['id_sell']        = (isset($data['response']['idventa']['IDVENTA']))            ? $data['response']['idventa']['IDVENTA']        : null;
        $result['date']           = (isset($data['response']['idventa']['FECHAHORA']))          ? $data['response']['idventa']['FECHAHORA']        : null;
        $result['price_per_unit'] = (isset($data['response']['promocion']['PRECIOXUNIDAD']))    ? $data['response']['promocion']['PRECIOXUNIDAD']  : null;
        $result['units']          = (isset($data['response']['promocion']['UNIDADES']))         ? $data['response']['promocion']['UNIDADES']       : null;
        $result['price']          = (isset($data['response']['promocion']['PRECIOXUNIDAD']))    ? $data['response']['promocion']['PRECIOXUNIDAD']  : null;
        $result['iva']            = (isset($data['response']['promocion']['IVA']))              ? $data['response']['promocion']['IVA']            : null;
        $result['total']          = (isset($data['response']['promocion']['IMPORTEFINAL']))     ? $data['response']['promocion']['IMPORTEFINAL']   : null;
        $result['discount']       = (isset($data['response']['promocion']['DESCUENTO']))        ? $data['response']['promocion']['DESCUENTO']      : null;


        return $result;
    }


    private function getStatus($posStatus)
    {

        $status = array();
        $posStatus = base_convert($posStatus, 16, 2);
        $mode = substr($posStatus, 0, 4);
        $state = substr($posStatus, 4, 4);

        if($state == '0100')          // 0100 Surtidor Bloqueado
            $status['state']='locked';

        if($mode == '1000')
            $status['mode']='prepaid';  // 1000 Prepago

        return $status;
    }



    /**
     * Exchange aadapter response for API response
     *
     * @param unknown $entity
     * @param unknown $data
     * @return unknown
     *
     * "No de Computador (2)": "01",
     * "No de Manguera (2)": "01",
     * "Codigo producto (2)": "01",
     * "Numero Suministro (6)": "000018",
     * "Precio Venta (6)": "010890",
     * "Importe (8)": "00250000",
     * "Litros (6)": "002296",
     * "Predeterminacion Pts (8)": "00000000",
     *  "Predeterminacion Lit (6)": "000000",
     * "Codigo A1 (2)": "01",
     * "Importe A1 (8)": "00300000",
     * "Litros A1 (6)": "002755",
     * "Codigo A2 (2)": "01",
     * "Importe A2 (8)": "00050000",
     * "Litros A2 (6)": "000443",
     * "Error (Libre) (2)": "84",
     * "Estado Surtidor (2)": "00",
     * "Error SCP-II - HCP-II (2)": "00",
     * "Error Surtidor (2)": "00",
     * "CR (13) (1)": "\r",
     * "LF (10) (1)": ""
     *
     */
    public function exchangePumpstatusArray($entity, $data)
    {
        $entity->idpump             = (isset($data['IDSURTIDOR']))       ? $data['IDSURTIDOR']        : null;
        $entity->status              = (isset($data['Estado Surtidor (2)']))       ? $data['Estado Surtidor (2)']        : null;

        //$entity->idopystation = (isset($data['idopystation']))    ? $data['idopystation']     : null;

        return $entity;
    }
}
