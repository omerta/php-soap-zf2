php-soap-zf2 - Autenticación
===========================

## 8.9 Usando «HTTP Basic Authentication» o «Digest Authentication» (PHP Cookbook o'really) ##

# Problema #

Nosotros deseamos  usar PHP para proteger parte de nuestro sitio web con «passwords». En lugar de guardar los «passwords» en un archivo externo y permitir que el servidor web maneje la autenticación, podemos dejar la verificación lógica del «password» al programa PHP.

# Solución #

Las variables globales $_SERVER['PHP_AUTH_USER'] y $_SERVER['PHP_AUTH_PW'] contienen el «username» y el «password» proporcionado por el usuario, si es que se han dado. Para denegar el acceso, «deny access», a la pagina se envía un «WWW-Authenticate header» en donde se identifica el «authentication realm» como parte de una respuesta con «status code 401», como se muestra en la Ejemplo 8-17.

```php
<?php
header('WWW-Authenticate: Basic realm="My Website");
header('HTTP/1.0 401 Unauthorized');
echo "You need to enter a valid username and password.";
exit();
?>
```

# Discusión #

Cuando un buscador ve un header 401, despliega una ventana emergente para completar el «username» y el «password». Estas credenciales (el «username» y el «password») si son aceptadas por el servidor son asociadas con el «realm» que esta en el «WWW-Authenticate header». El codigo que verifica las credenciales de autenticación necesita ser ejecutado antes de que cualqueir salida sea enviada al buscador, para luego enviar los «headers». Por ejemplo, podemos usar una función como ''pc_validate()'', que se muestra en el Ejemplo 8.18.

Ejemplo 8-18. pc_validate()
```php
funtion pc_validate($user,$pass) {
	/* replace with apropriate username and password checking,
	   such as checking a database */
	$users = array('david' => '123456',
	       	       'maria' => '234567');

    if (isset($users[$user]) && ($users[$user] == $pass)) {
	    return true;
	} else {
	    return false;
	}
}
?>
```

El Ejemplo 8-19 muestra como usar ''pc_validate()''.

Ejemplo 8-19. Usando una función de validación.
```php
<?php
if (! pc_validate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="My Website"');
	header('HTTP/1.0 401 Unauthorized');
	echo "You need to enter a valid username and password.";
	exit;
}
```	

Reemplace el contenido de la función ''pc_validate()'' con la lógica apropiada para determinar si un usuario introdujo la contraseña correcta. Podemos también cambiar la cadena que nombra al «realm» "My Website" y el mensaje que es impreso si el usuario presiona "cancel" en el cuadro de autenticación en su navegador por "You need to enter a valid username and password".

PHP 5.1.0 y las versiones posteriores soporta la «Digest authentication» que se añade a la «Basic authentication». Con la «Basic authentication», «username» y «password» son enviados en "claro" sobre la red, solo mínimamente ocultada por una codificación Base64. Con «Digest authentication», el «password» nunca es enviado desde el navegador al servidor. En su lugar, solo un «hash» del «password» junto con algunos otros valores es enviado. Esto reduce la posibilidad que el trafico de la red pueda ser capturado y repetido por un atacante. El incremento de seguridad que provee «Digest authentication» significa que el código necesario para implementar es más complicado que una comparación simple de «password». El ejemplo 8-20 provee funciones que implementan la «digest authentication» como se especifica en el RFC 2617.

Ejemplo 8-20. Usando «Digest authentication»
```php
/* replace with appropriate username and password checking,
   such as checking a database */

$users = array('david' => '123456',
               'maria' => '234567');

$realm = 'My website';

$username = pc_validate_digest($realm, $users);

```

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

* PHP Cookbook o'really
