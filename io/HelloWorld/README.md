Hello World PHP SOAP (php-soap-zf2)
===================================

Primeros paso para publicar servicios web con php usando soap y zend framework.

## Requerimientos ##

* Debian GNU/Linux 8.0
* PHP5 v5.6.2-1
* SOAP
* docblocks
* Zend Framework 2 (v2.3.2)

## Zend Framework ##

* AutoDiscovery, para la autogeneración del WSDL. Las Herramientas de autodiscovery utiliza «PHP docblocks» para determinar los valores y los tipos de datos que se regresan al cliente. 

## Código del Servidor ##

Regresaremos un array asociado. Generar el archivo WSDL depende de la declaración *class Universidad*, que luego es usada en la clase *class educacion* por el bloque del comentario que antecede la función *function universidad()*.

```php
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
```

## Código del cliente ##

```php
<?php
ini_set("soap.sdl_cache_enabled", "0");

$client = new SOAPClient('http://webservice.io/HelloWorld/server.php?wsdl');

try {
	var_dump($client->__getFunctions());
} catch(Exception $e) {
	print_r($e);
}

try {
	$result = $client->universidad();
	print_r($result);
	print "\n";
} catch(Exception $e) {
	print_r($e);
}

?>
```

SRC: [Descargar SRC](https://github.com/omerta/php-soap-zf2/tree/master/io/HelloWorld)

## Apache2 ##

Puede valerse de un VirtualHost, no olvide agregar la linea que corresponda al archivo /etc/hosts. 

1. a2enside 001-webservice.io.conf
2. service apache2 reload

### VirtualHost ###

```conf
<VirtualHost *:80>
        ServerName webservice.io

        DocumentRoot /srv/www/php-soap-zf2/io
        <Directory  /srv/www/php-soap-zf2/io/>
		  Options Indexes FollowSymLinks
		  AllowOverride All
		  Require all granted
        </Directory>

        LogLevel warn
        ErrorLog /var/log/apache2/webservice.io_error.log
        CustomLog /var/log/apache2/webservice.io_access.log combined
</VirtualHost>
```

## Bibliografía ##

* http://framework.zend.com/manual/2.0/en/modules/zend.soap.auto-discovery.html
* http://www.king-foo.be/2011/09/using-complex-types-with-zend_soap/
+ SOAP and PHP in 2014. Fuente: http://www.whitewashing.de/2014/01/31/soap_and_php_in_2014.html
+ https://github.com/yjwei/practices/tree/802ab1299f9c79cd67fc85bb40345a18fe0460f2/webservice
