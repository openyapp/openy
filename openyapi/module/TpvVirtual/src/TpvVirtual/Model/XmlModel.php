<?php

namespace TpvVirtual\Model;

use AP_XmlStrategy\View\Model\XmlModel as ParentModel;


class XmlModel
    extends ParentModel
{
    public function serialize(){
        $variables = $this->getVariables();
        $xml = new \DomDocument('1.0', 'UTF-8');
        $xml->loadXML($variables['response']);
        $result = $xml->saveXML();
        $result = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $result);
        return $result;
    }
}