<?php
ini_set("soap.wsdl_cache_enabled", "0");

function pc_authenticate_user($username, $password) {
//    $is_valid = true;
    $is_valid = false;
    
    if ($is_valid) {
        return true;
    } else {
        return false;
    }
}

class pc_SOAP_return_time {
    public function __construct() {
        if (! pc_authenticate_user($_SERVER['PHP_AUTH_USER'],
        $_SERVER['PHP_AUTH_PW'])) {
        throw new SOAPFault("Incorrect username and password combination.", 401);
        }
    }

    /**
	 * @return array array
	 */
	public function universidad()
	{
		$arreglo[]=array("cod_institucion"=>'300001',"siglas"=>'UCV',"cod_carrera"=>'306');
		$arreglo[]=array("cod_institucion"=>'300003',"siglas"=>'UNEFA',"cod_carrera"=>'308');
		//print_r($arreglo); // descomentar junto a: debug1
		return $arreglo;
	}

}

class educacion {
	/**
	 * @return array array
	 */
	public function universidad()
	{
		$arreglo[]=array("cod_institucion"=>'000001',"siglas"=>'UCV',"cod_carrera"=>'006');
		$arreglo[]=array("cod_institucion"=>'000003',"siglas"=>'UNEFA',"cod_carrera"=>'008');
		//print_r($arreglo); // descomentar junto a: debug1
		return $arreglo;
	}
}


// $return_philosopher=new educacion(); // descomentar junto a: debug1
// $return_philosopher->universidad(); // descomentar junto a: debug1

/* load framework ZF2 */
set_include_path('/srv/www/io/ZendFramework-2.3.2/library');
require_once 'Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));
$loader->registerPrefix('Zend', '/srv/www/io/ZendFramework-2.3.2/library');
$loader->register();

/* class autodiscover and Zend\Soap\Server */
if (isset ( $_GET ['wsdl' ] )) {
	$server = new Zend\Soap\AutoDiscover ();
	$server->setServiceName ( 'WebServiceEducacion' );
} else {
	$server = new Zend\Soap\Server ();
}

$uri = 'http://webservice.io/array/server.php';
//$server->setClass ('educacion');
$server->setClass ('pc_SOAP_return_time');
$server->setUri ( $uri );
$server->handle ();

?>