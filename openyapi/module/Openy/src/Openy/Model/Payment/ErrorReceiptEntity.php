<?php
namespace Openy\Model\Payment;

use Openy\Interfaces\ErrorEntityInterface;
use Openy\Model\Payment\ReceiptEntity as Receipt;
use Openy\Traits\Classes\ErrorEntityTrait;
use Openy\Traits\Classes\InheritTrait;


class ErrorReceiptEntity
	extends Receipt
	implements ErrorEntityInterface
{
	use InheritTrait;
	use ErrorEntityTrait;
}
