<?php

namespace Openy\Model\Hydrator;

use Openy\Model\Hydrator\NamingStrategy\RefuelSummaryNamingStrategy;
use Openy\Model\Hydrator\Strategy\PercentStrategy;
use Openy\Model\Hydrator\Strategy\MoneyStrategy;
use Openy\Model\Hydrator\Strategy\NumberStrategy;
use Openy\Options\PaymentOptions;
use Zend\Stdlib\Hydrator\Reflection;

class RefuelSummaryDetailsHydrator
    extends Reflection
{

    /**
     * Options about available taxes
     * @var \Openy\Options\PaymentOptions
     */
    protected $paymentOptions;

    public function __construct(PaymentOptions $paymentOptions){
        parent::__construct();
        $this->paymentOptions = $paymentOptions;
        $this->setNamingStrategy(new RefuelSummaryNamingStrategy());
        $this->addStrategy('litres', new NumberStrategy());
        $this->addStrategy('base', new MoneyStrategy());
        $this->addStrategy('tax_percent', new PercentStrategy());
        $this->addStrategy('tax_amt', new MoneyStrategy());
        $this->addStrategy('saving', new MoneyStrategy());
        $this->addStrategy('total', new MoneyStrategy());
    }


    public function hydrate(array $data, $object)
    {
        $object = parent::hydrate($data,$object);
        $taxes = $this->paymentOptions->getTaxes();
        foreach($taxes->toArray() as $tax => $tax_detail):
            if (floatval($object->tax_percent) == floatval($tax_detail['percent'])):
                if (array_key_exists($tax_detail['name'], $data)):
                    $object->tax = $tax;
                    break;
                endif;
            endif;
        endforeach;
        return $object;
    }


}