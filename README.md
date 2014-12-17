php-soap-zf2
============

Primeros paso para publicar servicios web con php usando soap y zend framework

* PHP5
* SOAP
* Zend Framework 2

## Apache2 ##

1. a2enside 008-webservice.io.conf
2. service apache2 reload

## 15.8 Usando SOAP con autenticación (PHP Cookbook o'really) ##

# Problema #

Podemos autenticar las peticiones SOAP permitiendo el acceso a nuestros servicios solo a los clientes de confianza.

# Solución #

Autenticar usando «HTTP Basic authentication»:

```php
<?php
// Your authentication logic
// Which is probably decoupled from your SOAP Server
function pc_authenticate_user($username, $password) {
	 // authenticate user
	 $is_valid = true // Implement your lookup here

	 if ($is_valid) {
	    return true;
	 } else {
	   return false;
	 }
}

class pc_SOAP_return_time {
      public function __construct() {
      	     // Throw SOAP fault for invalid username and password combo
	     if (! pc_authenticate_user($_SERVER['PHP_AUTH_USER'],
					$_SERVER['PHP_AUTH_PW'])) {
		throw new SOAPFault("Incorrect username and password combination.", 401);
	    }
      }

      // Rest of SOAP Server methods here
      
      $server = new SOAPServer(null,array('uri'=>"urn:pc_SOAP_return_time"));
      $server->setClass("pc_SOAP_return_time");

      $server->handle();
?>      
```

El ejemplo 15-12 define la función ''pc_authenticate_user()''. Esto no es una función especifica de SOAP, es un código estándar que maneja la autenticación del usuario. En este ejemplo, esta es una función separada y hacemos énfasis en que es una función de naturaleza separada. Sin embargo, podemos también definir esto como un ''private'' método dentro de pc_SOAP_return_time si desear guardar el estado o acceder a otras propiedades de los objetos.

Definiendo un constructor para ''pc_SOAP_return_time'', se fuerza al servidor SOAP que ejecute el código antes de manejar cualquier «SOAP headers» o «SOAP body». Dentro de __construct(), se llama a ''pc_authenticate_user'', pasa las variables a donde PHP guarda las credenciales de la «HTTP Basic Authentication», $_SERVER['PHP_AUTH_USER'] and $_SERVER['PHP_AUTH_PW'].

Si la autenticación falla, lanza un «SOAP fault». Recuerde que debe enviar un «HTTP Status Code» 500 cuando ocurra el «SOAP faults», así PHP no regresara un 401.

Podemos las credenciales para la «HTTP Basic authentication» en algo como este:
```php
<?php
$opts = array('location' => 'http://api.example.org/getTime',
      	      'uri'	 => 'urn:pc_SOAP_return_time',
	      'login'	 => 'elvis',
	      'password' => 'the-king')

$client = new SOAPClient(null, $opts);

$result = $client->__soapCall('return_time');
?>
```

El «SOAPClient» acepta las opciones «login» y «password». En estos se debe establece el «username» y «password» adecuado y ''ext/soap'' haré el resto usándolas en la «HTTP Basic authentication».

La otra opción es no usar la «HTTP Basic authentication», y en cambio pasar el «username» y el «password» en un «custom SOAP header». Esto agrega un control adicional sobre la información pudiendo extenderla fuera del protocolo HTTP.

La desventaja con respecto a la «HTTP Basic authentication» que es un concepto familiar es que las personas necesitan aprender como construir su propio «custom header».

## Bibliografía ##

* http://framework.zend.com/manual/2.0/en/modules/zend.soap.auto-discovery.html
* http://www.king-foo.be/2011/09/using-complex-types-with-zend_soap/
+ SOAP and PHP in 2014. Fuente: http://www.whitewashing.de/2014/01/31/soap_and_php_in_2014.html
+ https://github.com/yjwei/practices/tree/802ab1299f9c79cd67fc85bb40345a18fe0460f2/webservice
