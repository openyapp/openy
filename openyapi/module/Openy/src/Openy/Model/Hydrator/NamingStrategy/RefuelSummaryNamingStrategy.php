<?php

namespace Openy\Model\Hydrator\NamingStrategy;

use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy as ParentNamingStrategy;

class RefuelSummaryNamingStrategy
    extends MapperNamingStrategy
{
    public function __construct(){
        return parent::__construct(array(
                    'Product'        => 'product',
                    'Litros'         => 'litres',
                    'Precio'         => 'base',
                    'IVA'            => 'tax_percent',
                    'IVAAmount'      => 'tax_amt',
                    'Total'          => 'total',
                    'Ahorro'         => 'saving',
                                   ));
    }

}