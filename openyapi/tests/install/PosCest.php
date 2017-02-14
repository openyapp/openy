<?php
// use \InstallTester;
use Codeception\Util\Fixtures;
// use AspectMock\Test as test;
// use Codeception\Util\Stub;
// use Codeception\TestCase\Test as TestCase;
// use Codeception\Extension\SimpleOutput;

class PosCest //extends \Codeception\TestCase\Test
{
    protected $installePos;
    private $authorizationBearer = 'Bearer 79222c5976553dd03061b635db327a73d71e634c';
    private $authorizationBasic = 'Basic b3Blbnk6b3B5XzE=';
    
    public function _before(InstallTester $I)
    {   
    }
    
    public function _after(InstallTester $I)
    {
    }

    public function tryToTest(InstallTester $I)
    {
    }
    
    /************************************* PRIVATE ******************************************/
    
    private function _setHeaders(InstallTester $I)
    {
        $I->comment('I set this headers');
        $I->haveHttpHeader('Accept','application/hal+json');
        $I->haveHttpHeader('Content-Type','application/json');
        $I->haveHttpHeader('Authorization', $this->authorizationBearer);
        return;
    }
    
    private function _getFixtures(InstallTester $I, $value)
    {
        $I->comment('I read this fixtures: '.$value);
        return Fixtures::get($value);
    }
    
    /************************************* TEST *********************************************/
   
    public function checkClosest(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        $I->comment('------------------ Start -------------------');
        $I->comment('Closest Gas Station to 41.3941772,2.2002508 in: (0)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/closest/0?point=41.3941772,2.2002508');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"closeStationData"');
        $I->assertTrue($I->grabDataFromResponseByJsonPath('"station"')[0]===false);
        $I->comment('------------------ Next -------------------');
        $I->comment('Closest Gas Station to 41.3941772,2.2002508 in: (1)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/closest/1?point=41.3941772,2.2002508');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('"closeStationData"');
        $I->seeResponseContains('"station"');        
        $I->comment('------------------ Next -------------------');
        $I->comment('Closest: No point set on: (1)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/1');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('"detail":"Not location set"');
        $I->comment('------------------ End ---------------------');    
    }
    
    
    public function checkPing(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        foreach ($this->installedPos as $pos)
        {
            $I->comment('------------------ Start -------------------');
            $I->comment('Ping to offstation: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/ping/'.$pos);
            //$I->canSeeHttpHeader('Content-Type','application/json; charset=utf-8');
            $I->seeResponseCodeIs(200);
            //$I->seeResponseIsJson();
            $I->seeResponseContains('"ack datetime"');
            $token = $I->grabDataFromResponseByJsonPath('"ack datetime"');
            $I->comment('ack datetime: '.$token[0]);
            $I->comment('------------------ End ---------------------');
        }
    }
    
    public function checkConfig(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        foreach ($this->installedPos as $pos)
        {
            $I->comment('------------------ Start -------------------');
            $I->comment('Config to offstation: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/configuration/'.$pos);
            $I->seeResponseCodeIs(200);
            
            $I->comment('Station Ids');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"idopystation"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"idoffstation"')));
            
            $I->comment('Station POS');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"pos_name"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"pos_address"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"pos_locality"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"pos_province"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"pos_country"')));
            
            $I->comment('Station Invoice');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_name"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_address"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_locality"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_province"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_country"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_postalcode"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_document_type"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"inv_document"')));
            
            $I->comment('Station Internals');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"openy_payment_type"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"openy_terminal"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"openy_user_id"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"product_conversor"')));
            
            $I->comment('------------------ End ---------------------');
        }
    }
    
    public function checkPump(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        $I->comment('------------------ Start -------------------');
        $I->comment('Pump: Point, No off station : (0)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/0?point=41.3941772,2.2002508');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('"detail":"Station not defined"');
        $I->comment('------------------ Next -------------------');
        $I->comment('Pump: Point, No opy station: (BP station)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/152?point=41.3941772,2.2002508');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('"detail":"Not Opy station found"');
        $I->seeResponseContains('"closeStationData"');
        $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"stationData"')));
        $I->comment('------------------ End ---------------------');
        
        foreach ($this->installedPos as $pos)
        {
            $I->comment('------------------ Start -------------------');
            $I->comment('Pump: No point set: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/'.$pos);
            $I->seeResponseCodeIs(404);
            $I->seeResponseContains('"detail":"Not location set"');
            
            $I->comment('------------------ Next -------------------');
            $I->comment('Pump: Point, so far from: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/'.$pos.'?point=20.3941772,5.2002508');
            $I->seeResponseCodeIs(400);
            $I->seeResponseContains('"detail":"You are not close to this station"');
            $I->dontSeeResponseContains('"closeStationData"');
            $I->assertTrue(($I->grabDataFromResponseByJsonPath('"stationData"')[0]['idoffstation'])==$pos);
            $I->comment('------------------ Next -------------------');
            
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/closest/'.$pos.'?point=41.3941772,2.2002508');
            $lat = $I->grabDataFromResponseByJsonPath('"station"')[0]['ilat'];
            $lng =$I->grabDataFromResponseByJsonPath('"station"')[0]['ilng'];
            $idstation =$I->grabDataFromResponseByJsonPath('"station"')[0]['idstation'];
            $I->comment('Pump: Point, in location: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pump/'.$pos.'?point='.$lat.','.$lng);
            $I->seeResponseCodeIs(200);
            $I->seeResponseContains('"idopystation":"'.$idstation.'"');
            $I->dontSeeResponseContains('"closeStationData"');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"station"')));
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"_embedded"')[0]['pumps']));
            $I->comment('------------------ End ---------------------');
        }
    }
    
    public function checkPumpStatus(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        $I->comment('------------------ Start -------------------');
        $I->comment('Pump Status in: (0)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pumpstatus/0');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('"detail":"Not Opy station found"');
        $I->comment('------------------ Next -------------------');
        
        foreach ($this->installedPos as $pos)
        {
            $I->comment('------------------ Start -------------------');
            $I->comment('Pump Status in: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/pumpstatus/'.$pos);
            $I->seeResponseCodeIs(200);
            $I->seeResponseContains('"idopystation"');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"_embedded"')[0]['status']));
            $I->comment('------------------ End ---------------------');
        }
    }
    
    public function checkPrice(InstallTester $I)
    {
        $this->_setHeaders($I);
        $this->installedPos = $this->_getFixtures($I, 'installedPos');
        //$I->wantToTest('Ping to ');
        $I->comment('------------------ Start -------------------');
        $I->comment('Prices in: (0)');
        $I->sendGET($this->_getFixtures($I, 'url').'/opystation/price/0');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContains('"detail":"Not Opy station found"');
        $I->comment('------------------ Next -------------------');
    
        foreach ($this->installedPos as $pos)
        {
            $I->comment('------------------ Start -------------------');
            $I->comment('Prices in: '.$pos);
            $I->sendGET($this->_getFixtures($I, 'url').'/opystation/price/'.$pos);
            $I->seeResponseCodeIs(200);
            $I->seeResponseContains('"idopystation"');
            $I->assertTrue(!empty($I->grabDataFromResponseByJsonPath('"_embedded"')[0]['prices']));
            $I->comment('------------------ End ---------------------');
        }
    }
    
}
