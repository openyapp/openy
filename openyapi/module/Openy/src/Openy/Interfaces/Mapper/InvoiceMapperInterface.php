<?php

namespace Openy\Interfaces\Mapper;

use Openy\Interfaces\MapperInterface;

use Openy\V1\Rest\Fueltype\FueltypeCollection;

interface InvoiceMapperInterface
    extends MapperInterface
{
    /**
     * Sets Fuel types considered when building an invoice summary
     * @param FueltypeCollection $fuelTypes Collection of Fuel Types
     */
    public function setFuelTypes(FueltypeCollection $fuelTypes);

    /**
     * Gets Fuel types considered when building an invoice summary
     * @param FueltypeCollection $fuelTypes Collection of Fuel Types
     */
    public function getFuelTypes();

}