<?php
namespace Openy\Interfaces\Aware;

use Openy\Interfaces\Service\Core\AccessInterface;

interface CoreAccessServiceAwareInterface
{
    public function getCoreAccessService();

	public function setCoreAccessService(AccessInterface $coreAccessService);
}