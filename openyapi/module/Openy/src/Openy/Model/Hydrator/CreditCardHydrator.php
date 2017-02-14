<?php
namespace Openy\Model\Hydrator;

use Zend\Stdlib\Hydrator\Reflection;
use Zend\Stdlib\Hydrator\ObjectProperty;

class CreditCardHydrator extends Reflection
// Do not use "ObjectProperty" becasue credit card has lot of protected properties p
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (array_key_exists('expires', $data))
            $data['expires'] = (array_key_exists('cardexpyear', $data) && array_key_exists('cardexpmonth', $data)
                                ? str_pad($data['cardexpmonth'],2,'0',STR_PAD_LEFT).'/'.str_pad($data['cardexpyear'],2,'0',STR_PAD_LEFT)
                                : $data['expires']);

        return parent::hydrate($data, $object);
    }

}
