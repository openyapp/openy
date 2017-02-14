<?php
namespace Openy\V1\Rest\Receipt;

use Openy\Model\Hydrator\ReceiptHydrator as ParentHydrator;
use Openy\Model\Classes\BillingDataEntity;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;
use Zend\Stdlib\Hydrator\Reflection;

class ReceiptHydrator extends ParentHydrator
{
    public function hydrate(array $data, $object)
    {
        $billingDataEntity = new BillingDataEntity();
        $hydrator = new Reflection;
        $hydrator->setNamingStrategy(new MapperNamingStrategy(
                                             [  'issuername'    => 'billingName',
                                                'issuerid'      => 'billingId',
                                                'issueraddress' => 'billingAddress',
                                                //''            => 'billingWeb',
                                                //''            => 'billingMail',
                                                //''            => 'billingPhone',
                                                'logo'          => 'billingLogo',
                                             ]
                                             ));
        $billingDataEntity = $hydrator->hydrate($data,$billingDataEntity);
        $data['billingdata'] = json_encode($billingDataEntity);
        $object = parent::hydrate($data,$object);
        return $object;
    }

}