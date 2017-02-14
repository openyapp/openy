<?php

namespace Openy\Model\Invoice;

use Openy\Model\AbstractMapper;
use Openy\Model\Classes\BillingDataEntity;
use Openy\Model\Invoice\InvoiceEntity as Invoice;
use Openy\Model\Hydrator\InvoiceHydrator;
use Openy\Model\Hydrator\Strategy\NullStrategy;
//use Openy\Model\Hydrator\Strategy\PrefixStrategy;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Openy\Model\Payment\ReceiptCollection;
use Openy\V1\Rest\Fueltype\FueltypeEntity;
use Openy\V1\Rest\Fueltype\FueltypeCollection;
use Zend\Paginator\Adapter\NullFill;
use Openy\Model\Classes\RefuelSummaryDetailsEntity;
use Openy\Model\Hydrator\RefuelSummaryDetailsHydrator;
use Openy\Options\PaymentOptions;
use Openy\Interfaces\Mapper\InvoiceMapperInterface;
use Openy\Model\Hydrator\NamingStrategy\MapperNamingStrategy;

use Openy\Traits\Properties\OptionsTrait;
use Openy\Traits\Aware\PaymentOptionsServiceAwareTrait;
use Openy\Interfaces\Aware\PaymentOptionsServiceAwareInterface;
use Openy\Interfaces\Properties\OptionsInterface;
use Openy\Traits\Openy\Billing\DefaultCompanyBillingDataTrait;
use Openy\Interfaces\Aware\BillingOptionsServiceAwareInterface;
use Openy\Traits\Aware\BillingOptionsServiceAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class InvoiceMapper
	extends AbstractMapper
    implements InvoiceMapperInterface,
    		   BillingOptionsServiceAwareInterface,	
               PaymentOptionsServiceAwareInterface,
               ServiceLocatorAwareInterface
{

    use DefaultCompanyBillingDataTrait,
    	BillingOptionsServiceAwareTrait,
        PaymentOptionsServiceAwareTrait,
        ServiceLocatorAwareTrait;

    protected $tableName      = 'opy_invoice';
    protected $primary        = 'idinvoice';
    protected $entity         = 'Openy\Model\Invoice\InvoiceEntity';
    protected $collection     = 'Zend\Paginator\Paginator';
    protected $hydrator       = 'Openy\Model\Hydrator\AbstractEntityHydrator';
    protected $joinTableNames = [];

    /**
     * Options about available taxes
     * @var PaymentOptions
     */
    protected $paymentOptions;

    /**
     * Invoice billing data substracted from received options
     * @var [type]
     */
    protected $billingData;

    protected $idinvoicer;

    /**
     * Fuel types used for the invoice summaries
     * @var FuelTypeCollection
     */
    protected $fuelTypes;

    /**
     * {@inheritDoc}
     * @see  Openy\Interfaces\Mapper\InvoiceMapperInterface
     */
    public function setFuelTypes(FueltypeCollection $fuelTypes){
        $this->fuelTypes = $fuelTypes;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see  Openy\Interfaces\Mapper\InvoiceMapperInterface
     */
    public function getFuelTypes(){
        return $this->fuelTypes ? $this->fuelTypes : new FueltypeCollection(new NullFill());
    }

    protected function getFuelCodes(){
        return $this->getFuelTypes()->getEntitiesProperty('fuelcode');
    }

    public function getBillingData(){
        if (!isset($this->billingData))
            $this->billingData = $this->getDefaultCompanyBillingData($case='invoices');
        return $this->billingData;
    }

    public function setBillingData(BillingDataEntity $billingData){
        $this->billingData = $billingData;
        return $this;
    }

    public function setIdinvoicer($idinvoicer){
        $this->idinvoicer = $idinvoicer;
        return $this;
    }

    public function getIdinvoicer(){
        if (!isset($this->idinvoicer))
            $this->idinvoicer = $this->getDefaultCompanyId($case = 'invoices');
        return $this->idinvoicer;
    }

    protected function getHydratorInstance(){
        return new InvoiceHydrator($this->options);
    }

    protected function fetchAllGetHydratorInstance(){
        $hydrator = parent::fetchAllGetHydratorInstance();
//        $hydrator->addStrategy('invoicenumber',new PrefixStrategy($prefix=date('Y').'/'));
        return $hydrator;
    }

    protected function getDataHydratorInstance(){
        $datahydrator = parent::getDataHydratorInstance();
        $hydrator = $this->getHydratorInstance();

        foreach(['summary','taxes','billingdata'] as $prop)
            if ($hydrator->hasStrategy($prop))
                $datahydrator->addStrategy($prop,$hydrator->getStrategy($prop));

        return $datahydrator;
    }

    protected function insertGetHydratorInstance($data){
        if ($data instanceof BillingDataEntity){
            $hydrator =  new \Zend\Stdlib\Hydrator\Reflection();
            $namingStrategy = new MapperNamingStrategy([
                                            'issuername'    => 'billingName',
                                            'issuerid'      => 'billingId',
                                            'issueraddress' => 'billingAddress',
                                            'logo'          => 'billingLogo'
                                         ]);
            $hydrator->setNamingStrategy($namingStrategy);
            $hydrator->addFilter('usefull_billing_data',
                                function($property){
                                    return in_array($property,['issuername','issuerid','issueraddress','logo']);
                                },
                                FilterComposite::CONDITION_AND
                                );
        }
        else{
            $hydrator = parent::insertGetHydratorInstance();
            $hydrator->addStrategy('invoicenumber',new NullStrategy());
            $hydrator->addFilter('billing_data',function($prop){return $prop != 'billingdata';},FilterComposite::CONDITION_AND);
        }
        return $hydrator;
    }


    protected function updateGetHydratorInstance(){
        $hydrator = parent::updateGetHydratorInstance();
        if ($hydrator->hasStrategy('invoicenumber'))
            $hydrator->removeStrategy('invoicenumber');
        $hydrator->addStrategy('invoicenumber',new NullStrategy());
        return $hydrator;
    }



    public function insert($data, $date = null, $iduser = null){
        if ($data instanceof ReceiptCollection)
            $summary = $this->getSummaryFromReceiptCollection($data);
        $data = $this->insertGetEntityInstance();
        $data->iduser = $iduser ? (string)$iduser : $this->currentUser->get('iduser');
        $data->date = $date ? (string)$date : date('Y-m-d');        
        //$data->billingdata = $this->getBillingData();
        $data->summary = $summary;
        $data->idinvoicer = $this->getIdinvoicer();

        // AUTO INCREMENTAL KEY FIELD.
        // WILL BE FILLED BY parent::insert WITH THE DRIVER GENERATED VALUES
        $this->primary = 'invoicenumber';
        $result = parent::insert($data);
        $this->primary = 'idinvoice'; // UUID used as regular PK, but in deed is a secondary one
        return $result;
    }

        protected function getSummaryFromReceiptCollection(ReceiptCollection $receipts){
            $paymentOptions = $this->getPaymentOptions();
            $detailsydrator = new RefuelSummaryDetailsHydrator($paymentOptions);
            $invoice_summary = new RefuelSummaryDetailsEntity();
            $invoice_summary->products = array_fill_keys($this->getFuelCodes(),0.0);
            $invoice_summary->taxes = $paymentOptions->getTaxes()->toArray();      
                        
            foreach($receipts as $receipt){
                $summary = (array)$receipt->summary;
                $details = (array_key_exists('details',$summary) ? (array)$summary['details'] : []);
                $receipt_summary = $detailsydrator->hydrate($details,new RefuelSummaryDetailsEntity());
                extract(get_object_vars($receipt_summary));

                $invoice_summary->products[$product] += floatval($litres);
                $invoice_summary->total += floatval($total);
                $invoice_summary->saving += floatval($saving);

                $invoice_summary->taxes[$tax]['amount'] = $tax_amt + ( array_key_exists('amount',$invoice_summary->taxes[$tax])
                                                        ? $invoice_summary->taxes[$tax]['amount']
                                                        : 0.0 );
                $invoice_summary->taxes[$tax]['base'] = $base + ( array_key_exists('base',$invoice_summary->taxes[$tax])
                                                        ? $invoice_summary->taxes[$tax]['base']
                                                        : 0.0 );

            }

            $invoice_summary->products = array_filter($invoice_summary->products, function($val){return (floatval($val)>0.0);});
            $invoice_summary->taxes = array_filter($invoice_summary->taxes, function($val){return array_key_exists('base', $val) && (floatval($val['base'])>0.0);});

            foreach(get_object_vars($invoice_summary) as $prop => $value)
                if (!in_array($prop,['products','total','saving','taxes']))
                    unset($invoice_summary->{$prop});

            return $invoice_summary;
        }

    /**
     * Extracts billing data subvalues and populates values for insert
     * @param  StdClass &$data   Object with billingdata property
     * @param  \Zend\Db\Sql\Insert &$insert Insert to be prepared with values
     * @return \Zend\Db\Sql\Insert Input $insert var once ready for execution
     */
    protected function insertBuildSQLSetValues(&$data, &$insert){    	 
        $insert = parent::insertBuildSQLSetValues($data, $insert); 
        // We get our own hydrator for billing data
        // TODO move this behaviour to abstractHydrator by using Strategy Chains
        $data->billingdata = $this->getBillingData();
        $hydrator = $this->insertGetHydratorInstance($data->billingdata);

        $billingValues = $hydrator->extract($data->billingdata);
        $insert->values($billingValues, \Zend\Db\Sql\Insert::VALUES_MERGE);
                
        return $insert;
    }

    protected function insertBuildSQL(&$data){
    	$insert = new \Zend\Db\Sql\Insert($this->tableName);
    	$insert = $this->insertBuildSQLSetValues($data,$insert);
    	$insert = $this->insertBuildSQLSetWhere($data,$insert);
    	return $insert;
    }    
        
    
    
    /**
     * {@inheritDoc}
     * @see  Openy\Model\AbstractMapper
     */
    protected function fetchAllBuildQuery($filters){
        $query = parent::fetchAllBuildQuery($filters);
        $query->order('invoicenumber DESC');
        return $query;
    }

    /**
     * {@inheritDoc}
     * @see  Openy\Model\AbstractMapper
     */
    protected function fetchAllBuildQuerySetFilters($filters,&$query){
            parent::fetchAllBuildQuerySetFilters($filters,$query);

            if (count($filters)){
                if (isset($filters['until'])){
                    $hydr  = $this->fetchAllGetHydratorInstance();
                    $strat = $hydr->getStrategy('date');
                    $date  = date_create($filters['until']);
                    $date  = $date->add(new \DateInterval('P1D'));
                    $date  = $date->format($strat->getFormat());
                    $query ->where($this->tableAliasName.".date < '".$date."'");
                }
            }
            return $query;
        }
}