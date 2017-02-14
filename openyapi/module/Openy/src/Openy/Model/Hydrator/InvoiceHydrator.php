<?php

namespace Openy\Model\Hydrator;

use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\Strategy\UuidStrategy;
use Zend\Serializer\Adapter\Json;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\Strategy\SerializableStrategy;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Openy\Model\Classes\BillingDataEntity;
use Zend\Stdlib\Hydrator\Reflection;
use Openy\Model\Hydrator\AbstractEntityHydrator;

class InvoiceHydrator
	extends AbstractEntityHydrator //Reflection
{

    public function __construct(AbstractOptions $options){
        parent::__construct();
        $this->addStrategy('idinvoice',new UuidStrategy($options));
        $this->addStrategy('created',new CurrentTimestampStrategy());
        $this->addStrategy('date',new CurrentTimestampStrategy('Y-m-d'));
        $this->addStrategy('summary',new SerializableStrategy(new Json));
        $this->addStrategy('billingdata',new SerializableStrategy(new Json));
    }

    public function hydrate(array $data, $object){

        $billingDataEntity = new BillingDataEntity();
        $hydrator = new Reflection;
        $hydrator->setNamingStrategy(new MapperNamingStrategy(
                                             [ 'issuername'    => 'billingName',
                                               'issuerid'      => 'billingId',
                                               'issueraddress' => 'billingAddress',
                                               'logo'          => 'billingLogo'
                                             ]
                                             ));
        $billingDataEntity = $hydrator->hydrate($data,$billingDataEntity);
        $data['billingdata'] = json_encode($billingDataEntity);
        return parent::hydrate($data,$object);
    }
}