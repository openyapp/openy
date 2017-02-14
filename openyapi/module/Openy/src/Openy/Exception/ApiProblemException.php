<?php

namespace Openy\Exception;

/**
 * Could extends from Zend\Stdlib\Exception
 * 
 * BadMethodCallException
 * DomainException
 * ExtensionNotLoadedException
 * InvalidArgumentException
 * InvalidCallbackException
 * LogicException
 * RuntimeException
 * 
 * or from ZF\ApiProblem\Exception
 * 
 * DomainException 
 * 
 */

use ZF\ApiProblem\Exception\DomainException;

class ApiProblemException extends DomainException
{}