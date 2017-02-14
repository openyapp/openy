<?php

namespace Admin\Controller;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\Http\Request;


// use AssetManager\Service\AssetManager;

/**
 * Class ConsoleController
 *
 * @package AssetManager\Controller
 */
class ConsoleController extends AbstractActionController
{
    
    const documentRootDir = '/var/www/vhosts/.com/subdomains/openyapi';
//     const documentRootDir = '////openyapi';
    
    protected $eventIdentifier = 'NoSqliteauth';
    /**
     * @var \Zend\Console\Adapter\AdapterInterface console object
     */
    protected $console;
    protected $priceMapper;
    protected $stationMapper;
    protected $apicaller;
    

    /**
     * @var \AssetManager\Service\AssetManager asset manager object
     */
//     protected $assetManager;

    /**
     * @var array associative array represents app config
     */
//     protected $appConfig;

    /**
     * @param Console $console
     * @param AssetManager $assetManager
     * @param array $appConfig
     */
    public function __construct(Console $console, $priceMapper, $stationMapper, $apicaller)
//     public function __construct(Console $console, AssetManager $assetManager, array $appConfig)
    {
        $this->console          = $console;
        $this->priceMapper      = $priceMapper;
        $this->stationMapper    = $stationMapper;
        $this->apicaller    = $apicaller;
        
//         $this->assetManager = $assetManager;
//         $this->appConfig    = $appConfig;
    }

    /**
     * {@inheritdoc}
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed|ResponseInterface
     * @throws \RuntimeException
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null)
    {
        if (!($request instanceof ConsoleRequest)) {
            throw new \RuntimeException('You can use this controller only from a console!');
        }

        return parent::dispatch($request, $response);
    }

    /**
     * Dumps all assets to cache directories.
     */
    public function importAction()
    {
        
        $directory = 'data/officialstations/es/json/latest';
        $country = 'ES';
        
        $request    = $this->getRequest();
        $filename      = $request->getParam('filename', false);
        $verbose    = $request->getParam('verbose', false) || $request->getParam('v', false);

        $fueltypes = array(
            'BIE.json'=>9,
            'BIO.json'=>7,
            'G95.json'=>8,
            'G98.json'=>2,
            'GLP.json'=>10,
            'GNC.json'=>11,
            'GOA.json'=>3,
            'GOB.json'=>5,
            'GOC.json'=>6,
            'GPR.json'=>1,
            'NGO.json'=>4
        );
        
        // purge cache for every configuration
        if ($filename)
        {
//             $result = $this->priceMapper->truncate();
            $this->priceMapper->countChanges = 0;
            $this->priceMapper->cycleFailsFilename();
            
            //                 echo $iterator->getFilename() . "\n";
            echo $filename . "\n";
            if($filename == 'All')
            {
                foreach($fueltypes as $file => $id)
                {
                    echo $file."\n";
                    $content = file_get_contents($directory.'/'.$file);
                    $content = json_decode($content);
                    foreach($content as $key => $value)
                    {
//                         $value['created'] = '2014-06-14';
                        $result = $this->stationMapper->save($value);    
                        $price = array('idoffstation'=>$result,
                                       'price'=>$value->price,
                                       'idfueltype'=>$fueltypes[$file]
                        );
                        $result = $this->priceMapper->save($price);
                    }
                }
            }
            else 
            {
                //                 $content = file_get_contents($directory.'/'.$iterator->getFilename());
                $content = file_get_contents($directory.'/'.$filename);
                $content = json_decode($content);
                foreach($content as $key => $value)
                {
                    $result = $this->stationMapper->save($value);
                    $price = array('idoffstation'=>$result,
                                   'price'=>$value->price,
                                   'idfueltype'=>$fueltypes[$filename]
                    );
                    $result = $this->priceMapper->save($price);
                }
            }
            echo $this->priceMapper->countChanges;
        }

        $this->output('Collecting all assets...', $verbose);
        $this->output(sprintf('Importing finished...', $verbose));
    }

  

    /**
     * Outputs given $line if $verbose i truthy value.
     * @param $line
     * @param bool $verbose verbose flag, default true
     */
    protected function output($line, $verbose = true)
    {
        if ($verbose) {
            $this->console->writeLine($line);
        }
    }
    
//     public function monitorRaiseAction()
//     {
        
//         $header =array('Accept' => 'application/json',
//                        'Content-Type'=>'application/json',
//                        'Authorization'=>'Basic b3Blbnk6b3B5XzE='
//         );
//         print_r($header);
//         $this->apicaller->setHeaders($header);
//         $url = sprintf('http:///opystation/monitorraisepump/1/3');
//         $value = $this->apicaller->getResponse($url,  array(), Request::METHOD_GET);
//         print_r($value);
//         return $value;
        
        
//         echo "kaka";
        
//     }
  
    
    # setup a global file pointer
    //$GlobalFileHandle = null;
    
    private function saveRemoteFile($url, $filename) 
    {
        
    
//         set_time_limit(0);
    
        # Open the file for writing...
        $GlobalFileHandle = fopen($filename, 'w');
    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FILE, $GlobalFileHandle);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        
//         curl_setopt($curl, CURLOPT_HEADER, 0);
//         curl_setopt($curl, CURLOPT_USERAGENT, "MY+USER+AGENT"); //Make this valid if possible
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); # optional
//         curl_setopt($curl, CURLOPT_TIMEOUT, -1); # optional: -1 = unlimited, 3600 = 1 hour
//         curl_setopt($curl, CURLOPT_VERBOSE, false); # Set to true to see all the innards
    
        # Only if you need to bypass SSL certificate validation
//         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
        # Assign a callback function to the CURL Write-Function
//         curl_setopt($curl, CURLOPT_WRITEFUNCTION, 'curlWriteFile');
    
        # Exceute the download - note we DO NOT put the result into a variable!
        curl_exec($curl);
        
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($httpCode == 404) 
        {
            //touch('cache/404_err.txt');
        }
        else
        {
        
//             touch('cache/'.rand(0, 99999).'--all_good.txt');
            $contents = curl_exec($curl);
            $contents = $this->prettyPrint($contents);
            fwrite($GlobalFileHandle, $contents);
            
        }
        
        # Close CURL
        curl_close($curl);
    
        # Close the file pointer
        fclose($GlobalFileHandle);
            chmod($filename, 0750);
//             chown($filename, 'daemon');
//             chgrp($filename, 'staff');
        return $contents;
    }
    
//     private function curlWriteFile($cp, $data) {
//         global $GlobalFileHandle;
//         $len = fwrite($GlobalFileHandle, $data);
//         return $len;
//     }
    
    private function prettyPrint( $json )
    {
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = NULL;
        $json_length = strlen( $json );
    
        for( $i = 0; $i < $json_length; $i++ ) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if( $ends_line_level !== NULL ) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ( $in_escape ) {
                $in_escape = false;
            } else if( $char === '"' ) {
                $in_quotes = !$in_quotes;
            } else if( ! $in_quotes ) {
                switch( $char ) {
                    case '}': case ']':
                        $level--;
                        $ends_line_level = NULL;
                        $new_line_level = $level;
                        break;
    
                    case '{': case '[':
                        $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;
    
                    case ':':
                        $post = " ";
                        break;
    
                    case " ": case "\t": case "\n": case "\r":
                        $char = "";
                        $ends_line_level = $new_line_level;
                        $new_line_level = NULL;
                        break;
                }
            } else if ( $char === '\\' ) {
                $in_escape = true;
            }
            if( $new_line_level !== NULL ) {
                $result .= "\n".str_repeat( "\t", $new_line_level );
            }
            $result .= $char.$post;
        }
    
        return $result;
    }
    
    public function getmineturAction()
    {
        $request    = $this->getRequest();
        $type       = $request->getParam('type', false);
        $verbose    = $request->getParam('verbose', false) || $request->getParam('v', false);
        
        
        
        if($type == 'GasStationPrice')
        {
            $composeCurl = ConsoleController::documentRootDir.'/bin/open_gasolineras.py -d '.ConsoleController::documentRootDir.'/data/officialstations/es -r '.ConsoleController::documentRootDir.'/data/officialstations/es';
            echo $composeCurl;
            $salida = exec($composeCurl);
            $this->output(sprintf('STEP 1 Get Stations Price finished', $salida));
        }   
        
        if($type == 'EstacionesTerrestres')
        {
            $url ='https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/';
            echo $url;
            $filename = ConsoleController::documentRootDir.'/data/officialstations/address.json';
            $salida = $this->saveRemoteFile($url, $filename);
            $this->output(sprintf('STEP 1 Get Stations address finished', $salida));            
        }
        
        if($type == 'PostesMaritimos')
        {
            $url ='https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/PostesMaritimos/';
            $filename = ConsoleController::documentRootDir.'/data/officialstations/marinepostaddress.json';
            $salida = $this->saveRemoteFile($url, $filename);
            $this->output(sprintf('STEP 1 Get MarinePost address finished', $salida));            
        }
        
        $this->output(sprintf('Importing finished...', $salida));
    }
    
    
    
    public function addressAction()
    {
        
//         $composeCurl = 'php ////openyapi/public/index.php import locality';
//         echo $composeCurl;
//         $salida = exec($composeCurl);
//         sleep(40);
//         $this->output(sprintf('STEP 2 Import Locality finished', $salida));
        
        $directory = ConsoleController::documentRootDir.'/data/officialstations';
        $country = 'ES';
        $file = 'address.json';
        
        $request    = $this->getRequest();
        $filename   = $request->getParam('filename', false);
        $verbose    = $request->getParam('verbose', false) || $request->getParam('v', false);
        
        echo $directory.'/'.$file."\n";
        
        $content = file_get_contents($directory.'/'.$file);
//         print_r($content);
        $content = json_decode($content);
        $ListaEESSPrecio = (array) $content->ListaEESSPrecio;
//         print_r($content);
        foreach($ListaEESSPrecio as $key => $value)
        {
            //                         $value['created'] = '2014-06-14';
            $result = $this->stationMapper->saveAddress((array)$value);
//             print_r($value);
        }
    }
    
    public function municipalityAction()
    {
        $directory = ConsoleController::documentRootDir.'/data/officialstations';
        $country = 'ES';
        $file = 'address.json';
    
        $request    = $this->getRequest();
        $filename   = $request->getParam('filename', false);
        $verbose    = $request->getParam('verbose', false) || $request->getParam('v', false);
    
        echo $directory.'/'.$file."\n";
    
        $content = file_get_contents($directory.'/'.$file);
        //         print_r($content);
        $content = json_decode($content);
        $ListaEESSPrecio = (array) $content->ListaEESSPrecio;
        //         print_r($content);
        foreach($ListaEESSPrecio as $key => $value)
        {
            //                                     $value['created'] = '2014-06-14';
            $result = $this->stationMapper->saveMunicipality($value);
        }
    }
    
    public function localityAction()
    {
        $directory = ConsoleController::documentRootDir.'/data/officialstations';
        $country = 'ES';
        $file = 'address.json';
        
        $request    = $this->getRequest();
        $filename   = $request->getParam('filename', false);
        $verbose    = $request->getParam('verbose', false) || $request->getParam('v', false);
        
        echo $directory.'/'.$file."\n";
        
        $content = file_get_contents($directory.'/'.$file);
//         print_r($content);
        $content = json_decode($content);
        $ListaEESSPrecio = (array) $content->ListaEESSPrecio;
//         print_r($content);
        foreach($ListaEESSPrecio as $key => $value)
        {
//                                     $value['created'] = '2014-06-14';
            $result = $this->stationMapper->saveLocality($value);                        
        }
    }
}
