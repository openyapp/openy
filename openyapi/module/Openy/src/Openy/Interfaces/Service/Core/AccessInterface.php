<?php

namespace Openy\Interfaces\Service\Core;

interface AccessInterface
{

	/**
	 * Returns an access instance with the recorded timestamp, ip, client and token
	 * @return Openy\Model\Core\AccessEntity  Current session access
	 */
	public function getCurrentAccess();
}