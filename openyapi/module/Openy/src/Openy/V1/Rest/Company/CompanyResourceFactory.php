<?php
namespace Openy\V1\Rest\Company;

class CompanyResourceFactory
{
    public function __invoke($services)
    {
        return new CompanyResource($services->get('Openy\V1\Rest\Company\CompanyMapper'));
    }
}
