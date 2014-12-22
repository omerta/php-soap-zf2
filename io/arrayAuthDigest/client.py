#!/usr/bin/env python
# https://fedorahosted.org/suds/wiki/Documentation#FIXINGBROKENSCHEMAs
 
from suds.client import Client
from suds.xsd.doctor import ImportDoctor, Import
imp = Import('http://schemas.xmlsoap.org/soap/encoding/')
imp.filter.add('http://webservice.io/array/server.php')
d = ImportDoctor(imp)
url = 'http://webservice.io/array/server.php?wsdl'

client = Client(url, doctor=d)
print Client

arreglo = client.service.universidad()
print arreglo
