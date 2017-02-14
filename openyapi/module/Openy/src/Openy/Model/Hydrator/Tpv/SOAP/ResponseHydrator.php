<?php

namespace Openy\Model\Hydrator\Tpv\SOAP;

use Zend\Stdlib\Hydrator\Reflection;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\Strategy\DsAmountStrategy;
use Openy\Model\Hydrator\Strategy\UnsignedIntZeroPadStrategy;

class ResponseHydrator extends Reflection
{
	public function __construct(){
		parent::__construct();
        $this->addstrategy('received', new CurrentTimestampStrategy());
	}

 	/**
     * {@inheritDoc}
     *
     * Sets created field with current datetime if empty
     *
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
    	$this->setNamingStrategy(new MapperNamingStrategy(
    	                         [  'Ds_Response'   => 'response',
                                    'codigo'  => 'code',
                                    'Ds_AuthorisationCode' => 'authorizationcode',
                                    'Ds_Merchant_Identifier'=>'token',
                                    'Ds_Order' => 'transactionid',
                                    'Ds_Merchant_Order' => 'transactionid',
                                    'DS_MERCHANT_ORDER' => 'transactionid',

    	                         ]));
    	$data['received'] = null;

        return parent::hydrate($data,$object);
    }

}
