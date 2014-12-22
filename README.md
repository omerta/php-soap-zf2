php-soap-zf2
============

Primeros paso para publicar servicios web con php usando soap y zend framework

* PHP5
* SOAP
* Zend Framework 2

## Zend Framework ##

* AutoDiscovery, para la autogeneración del WSDL. Las Herramientas de autodiscovery utilizan PHP docblocks para determinar los valores y sus tipos que se regresarán.

## Apache2 ##

1. a2enside 008-webservice.io.conf
2. service apache2 reload

## Bibliografía ##

* http://framework.zend.com/manual/2.0/en/modules/zend.soap.auto-discovery.html
* http://www.king-foo.be/2011/09/using-complex-types-with-zend_soap/
+ SOAP and PHP in 2014. Fuente: http://www.whitewashing.de/2014/01/31/soap_and_php_in_2014.html
+ https://github.com/yjwei/practices/tree/802ab1299f9c79cd67fc85bb40345a18fe0460f2/webservice
