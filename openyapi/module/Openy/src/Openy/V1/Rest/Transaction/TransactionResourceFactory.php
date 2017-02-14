<?php
namespace Openy\V1\Rest\Transaction;

class TransactionResourceFactory
{
    public function __invoke($services)
    {
        return new TransactionResource($services->get("Openy\Model\Transaction\TransactionMapper"));
    }
}
