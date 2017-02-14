<?php

namespace Openy\Model\Classes;

class RefuelSummaryDetailsEntity
{

    /**
     * Cantidad servida de un producto
     * @var Float
     */
    public $litres = 0.0;
    /**
     * Código (nombre) del producto (carburante) servido en el repostaje
     * @var String
     */
    public $product;
    /**
     * Base imponible en el importe de un repostaje
     * @var Float
     */
    public $base = 0.0;

    /**
     * Identificador del tipo de tasa aplicada a un repostaje
     *
     */
    public $tax;

    /**
     * Importe correspondiente al pago de la tasa sobre el importe total del respostaje
     * @var Float
     */
    public $tax_amt = 0.0;

    /**
     * Porcentaje
     * @var Integer
     */
    public $tax_percent = 0.0;

    /**
     * Importe total
     * @var Float
     */
    public $total = 0.0;

    /**
     * Ahorro total
     * @var Float
     */
    public $saving = 0.0;

}