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