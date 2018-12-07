# evertracker-soap-client-generator
A wrapper script to generate PHP client classes for SOAP web services using wsdl2phpgenerator/wsdl2phpgenerator

<b>Usage:</b><br>
./public/generate.sh -h

evertracker PHP client generator for SOAP services<br>
Usage:<br>
  generate [options]<br>
Options (<b>bold</b> are mandatory):<br>
* <b>-w|--service-wsdl=<URL></b><br>
  the URL of the SOAP service's WSDL file<br>
* <b>-n|--namespace=<namespace></b><br>
  the namespace of the generated PHP client classes<br>
* <b>-o|--output-folder=<folder path></b><br>
  the folder path where the generated PHP client classes will be written<br>
*  -a|--authentication=<authentication type; 0=SOAP_AUTHENTICATION_BASIC; 1=SOAP_AUTHENTICATION_DIGEST><br>
  the default authentication type; the client service class will have this option as default<br>
*  -u|--username=<username><br>
  it's the default username that authenticates with basic http authentication, for the SOAP client<br>
*  -p|--password=<password><br>
  it's the default password to authenticate the user with the basic http authentication, for the SOAP client<br>
*  -c|--connection-timeout=<seconds><br>
  it's the default connection timeout in seconds for the SOAP client<br>
*  -h|--help<br>
  displays this help<br>
<br>
<b>Example</b>

./public/generate.sh -w=\~/Calculator.wsdl -n=Evertracker\\SoapClient -o=\~/Calculator/SoapClient/ -a=0 -u=vlad -p=password -c=30
