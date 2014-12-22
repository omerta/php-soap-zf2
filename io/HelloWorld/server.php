<?php
ini_set("soap.wsdl_cache_enabled", "0");

class Universidad {
	/** @var int */
	public $cod_institucion;
	/** @var string */
	public $siglas;
	/** @var int */
	public $cod_carrera;
}

class educacion{
	/**
	 * @return Universidad[] arrayOfLetters
	 */
	public function universidad()
	{
		$arreglo[]=array("cod_institucion"=>'000001',"siglas"=>'UCV',"cod_carrera"=>'006');
		$arreglo[]=array("cod_institucion"=>'000002',"siglas"=>'UCV',"cod_carrera"=>'007');
		$arreglo[]=array("cod_institucion"=>'000003',"siglas"=>'UNEFA',"cod_carrera"=>'008');
		return $arreglo;
	}
}

/* load framework ZF2 */
set_include_path('/srv/www/io/ZendFramework-2.3.2/library');
require_once 'Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));
$loader->registerPrefix('Zend', '/srv/www/io/ZendFramework-2.3.2/library');
$loader->register();

/* class autodiscover and Zend\Soap\Server */
if (isset ( $_GET ['wsdl' ] )) {
	$strategy = new Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeComplex ();
	$server = new Zend\Soap\AutoDiscover ( $strategy );
	$server->setServiceName ( 'WebServiceEducacion' );
} else {
	$server = new Zend\Soap\Server ();
}

$uri = 'http://webservice.io/HelloWorld/server.php';
$server->setClass ('educacion');
$server->setUri ( $uri );
$server->handle ();

?>