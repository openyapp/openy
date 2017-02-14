<?php

namespace Openy\Model\Hydrator\Tpv\SOAP;

use Zend\Stdlib\Hydrator\Reflection; //ObjectProperty;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Hydrator\Strategy\DsAmountStrategy;
use Openy\Model\Hydrator\Strategy\UnsignedIntZeroPadStrategy;
use Zend\Stdlib\AbstractOptions;


class RequestHydrator extends Reflection//ObjectProperty
{
    protected $tpvoptions;

	public function __construct(AbstractOptions $tpvoptions){
		parent::__construct();

		$this->addFilter('hidden',function($property){
			return !in_array($property,$hidden = ['idcreditcard','created','updated','function',
			                 					  'url','sent','idsoaprequest']);
		});
		$this->addStrategy('amount', new DsAmountStrategy());
        $this->addStrategy('terminal',new UnsignedIntZeroPadStrategy(3,UnsignedIntZeroPadStrategy::PAD_WHEN_EXTRACTING));
        //$this->addStrategy('order',new UnsignedIntZeroPadStrategy(4,UnsignedIntZeroPadStrategy::PAD_WHEN_EXTRACTING));

        $this->addstrategy('sent', new CurrentTimestampStrategy());
        $this->tpvoptions = $tpvoptions;
	}


  /**
     * {@inheritDoc}
     *
     * By default object will fill its created field with current datetime if empty.
     * It will do the same when created has a value but not updated
     *
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function extract($object)
    {
    	$this->setNamingStrategy(new MapperNamingStrategy([
                                                            'transaction_type'=>'transactionType',
                                                            'order'          => 'transactionid',
                                                            'merchant_code'   => 'merchantcode',
                                                            'identifier'          => 'token',
    	                         							]));

        if (empty($object->token) && !(empty($object->cvv) || empty($object->expiry) || empty($object->pan)))
            $object->token = $this->tpvoptions->getDefaults("merchant_identifier");

        return parent::extract($object);
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
        $this->setNamingStrategy(new MapperNamingStrategy([ 'Ds_AuthorisationCode' => 'authorizationcode',
                                                            'Ds_Response'       => 'lastresponse',
                                                            'codigo'          => 'lastcode',
                                                            'merchant_code'   => 'merchantcode',
                                                            'identifier'          => 'token',
                                                            'Ds_Merchant_Identifier'=>'token',
                                                            'transaction_type'=>'transactionType',
                                                            'order'          => 'transactionid',
                                                            ]));

        return parent::hydrate($data,$object);
    }

}
