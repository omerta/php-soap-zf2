<?php
ini_set("soap.wsdl_cache_enabled", "0");

class pc_SOAP_return_time {
    public function __construct() {
        /* if (! pc_authenticate_user($_SERVER['PHP_AUTH_USER'], */
        /* $_SERVER['PHP_AUTH_PW'])) { */
        /* throw new SOAPFault("Incorrect username and password combination.", 401); */
        
        $users = array('user1' => '123',
                       'user2' => '456');
        $realm = 'My website';

        $username = pc_validate_digest($realm,$users);

/** 
 * La ejecución del scrip nunca sobrepasa este punto 
 * si no se provee información de autenticación correcta
 */
        print "Hello, " . htmlentities($username);

/**
 * Falla si el 'digest' no fue provisto por el cliente
 * Falla si se falla en procesar el 'digest'
 */
        function pc_validate_digest($realm,$users) {
            if (!isset($_SERVER['PHP_AUTH_DIGEST'])) {
                pc_send_digest($realm);
            }

            $username = pc_parse_digest($_SERVER['PHP_AUTH_DIGEST'], $realm, $users);
            if ($username === false) {
                pc_send_digest($realm);
            }
            
            return $username;
        }

/**
 *
 */
function pc_send_digest($realm) {
    header('HTTP/1.0 401 Unauthorized');
    $nonce = md5(uniqid());
    $opaque = md5($realm);
    header("WWW-Authenticate: Digest realm=\"$realm\" qop=\"auth\" ".
           "nonce=\"$nonce\" opaque=\"$opaque\"");
    echo "You need to enter a valid username and password.";
    exit;
}

/**
 * 
 */
function pc_parse_digest($digest,$realm,$users) {
    $digest_info = array();
    foreach (array('username','uri','nonce','cnonce','response') as $part) {
        if (preg_match('/'.$part.'=([\'"]?)(.*?)\1/', $digest, $match)) {
            $digest_info[$part] = $match[2];
        } else {
            return false;
        }
    }

    if (preg_match('/qop=auth(,|$)/', $digest)) {
        $digest_info['qop'] = 'auth';
    } else {
        return false;
    }

    if (preg_match('/nc=(0-9a-f]{8})(,|$)/', $digest, $match)) {
        $digest_info['nc'] = $match[1];
    } else {
        return false;
    }

    $A1 = $digest_info['username'] . ':' . $realm . ':' . $users[$digest_info['username']];
    $A2 = $_SERVER['REQUEST_METHOD'] . ':' . $digest_info['uri'];
    $request_digest = md5(implode(':', array(md5($A1), $digest_info['nonce'], $digest_info['nc'], $digest_info['cnonce'], $digest_info['qop'], md5($A2))));

    if ($request_digest != $digest_info['response']) {
        return false;
    }

    return $digest_info['username'];
}


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

$uri = 'http://webservice.io/arrayAuthDigest/server.php';
//$server->setClass ('educacion');
$server->setClass ('pc_SOAP_return_time');
$server->setUri ( $uri );
$server->handle ();


?>