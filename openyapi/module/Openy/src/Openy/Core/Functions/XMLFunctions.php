<?php

namespace Openy\Core\Functions;

final class XMLFunctions{

	/**
	 * @link   http://php.net/manual/en/function.libxml-get-errors.php
	 */
	static function display_xml_error($error,$xml){
		$return  = $xml[$error->line - 1] . "\n";
	    $return .= str_repeat('-', $error->column) . "^\n";

	    switch ($error->level) {
	        case LIBXML_ERR_WARNING:
	            $return .= "Warning $error->code: ";
	            break;
	         case LIBXML_ERR_ERROR:
	            $return .= "Error $error->code: ";
	            break;
	        case LIBXML_ERR_FATAL:
	            $return .= "Fatal Error $error->code: ";
	            break;
	    }

	    $return .= trim($error->message) .
	               "\n  Line: $error->line" .
	               "\n  Column: $error->column";

	    if ($error->file) {
	        $return .= "\n  File: $error->file";
	    }

	    return "$return\n\n--------------------------------------------\n\n";
	}

}



