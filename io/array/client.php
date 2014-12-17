<?php
ini_set("soap.sdl_cache_enabled", "0");

$client = new SOAPClient('http://webservice.io/array/server.php?wsdl', array('login'    => "some_name",
'password' => "some_password"));

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