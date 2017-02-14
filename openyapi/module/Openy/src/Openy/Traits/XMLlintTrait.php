<?php

namespace Openy\Traits;

use Openy\Core\Functions\XMLFunctions;

trait XMLlintTrait{
    protected function XMLlint($xml){
        $arrxml = explode("\n", $xml);
        libxml_use_internal_errors(true);
        $doc = simplexml_load_string($xml);

        if (!$doc) {
            $errors = libxml_get_errors();
            ob_start();
            foreach ($errors as $error) {
                echo XMLFunctions::display_xml_error($error, $arrxml);
            }
            $exception_msg = ob_get_contents();
            ob_end_clean();

            libxml_clear_errors();

            if (count($errors))
                throw new \ZendXml\Exception\RuntimeException($exception_msg,1);

            return false;
        }
        else
            return $doc;
    }
}