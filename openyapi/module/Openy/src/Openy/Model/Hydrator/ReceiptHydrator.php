<?php

namespace Openy\Model\Hydrator;

use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;
use Openy\Model\Hydrator\Strategy\UuidStrategy;
use Zend\Serializer\Adapter\Json;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Reflection;
use Zend\Stdlib\Hydrator\Strategy\SerializableStrategy;

use Openy\Model\Hydrator\AbstractEntityHydrator;

class ReceiptHydrator
	extends AbstractEntityHydrator //Reflection
{

	public function __construct(AbstractOptions $options){
		parent::__construct();
	    $this->addStrategy('receiptposid',new UuidStrategy($options));
        $this->addStrategy('date',new CurrentTimestampStrategy());
        $this->addStrategy('summary',new SerializableStrategy(new Json));
        $this->addStrategy('billingdata',new SerializableStrategy(new Json));
        $this->addStrategy('taxes',new SerializableStrategy(new Json));
        $this->addFilter('NON_IMPLEMENTED_PROPERTIES',
                         function($property){return $property != 'template';},
                         FilterComposite::CONDITION_AND
                        );
	}

}