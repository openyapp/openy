<?php

namespace Openy\Model\Company;

use Openy\Model\AbstractEntity;

class CompanyEntity
    extends AbstractEntity
{
	public $idcompany;
	public $company;
    public $description;
    public $idinvoicer;
    public $secret;
    public $merchantcode;
    public $terminal;

    // Following attributes are reserved for API REST purposes
    //public $billingdata; // Billing data including address, logo, comercial ID, ...
	//public $stations; // Collection of stations belonging this company

    const pk = 'idcompany';
    const sample =  <<<HEREDOC
    {
        "idcompany": "1",
        "company": "test",
        "description": "test for tpv",
        "idinvoicer": null,
        "secret": null,
        "merchantcode": null,
        "terminal": null
    }
HEREDOC;
}