<?php
ini_set("soap.sdl_cache_enabled", "0");

#$client = new SOAPClient('http://webservice.io/arrayAuthDigest/server.php?wsdl', array('authentication' => SOAP_AUTHENTICATION_DIGEST,'login'    => "user1",'password' => "123"));
$client = new SOAPClient('http://webservice.io/arrayAuthDigest/server.php?wsdl', array('authentication' => SOAP_AUTHENTICATION_BASIC,'login'    => "user1",'password' => "123"));

/* try { */
/*     var_dump($client->__soapCall('return_time')); */
/*     } catch(Exception $e) { */
/*         print_r($e); */
/*     } */

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

/* try { */
/* 	$result = $client->universidad_in(); */
/* 	print_r($result); */
/* 	print "\n"; */
/* } catch(Exception $e) { */
/* 	print_r($e); */
/* } */

?>