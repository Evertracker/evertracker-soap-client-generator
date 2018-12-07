<?php

include_once "../vendor/autoload.php";

$serviceWsdlUrl = null;
$clientClassesNamespace = null;
$clientClassesOutputFolder = null;
$authenticationType = null;
$username = null;
$password = null;
$connectionTimeout = null;

if (defined('STDIN')) {
    if (isset($argv[1])) {
        parse_str($argv[1], $_GET);
    }
}

$args = array(
    'serviceWsdlUrl' => FILTER_SANITIZE_URL,
    'clientClassesNamespace' => FILTER_SANITIZE_STRING,
    'clientClassesOutputFolder' => ['filter' => FILTER_SANITIZE_URL, 'flags' => FILTER_FLAG_PATH_REQUIRED],
    'authenticationType' => FILTER_SANITIZE_NUMBER_INT,
    'username' => FILTER_SANITIZE_STRING,
    'password' => FILTER_SANITIZE_STRING,
    'connectionTimeout' => FILTER_SANITIZE_NUMBER_INT
);
$params = filter_var_array($_GET, $args);
if (isset($params['serviceWsdlUrl'])) {
    $serviceWsdlUrl = $params['serviceWsdlUrl'];
}
if (isset($params['clientClassesNamespace'])) {
    $clientClassesNamespace = $params['clientClassesNamespace'];
}
if (isset($params['clientClassesOutputFolder'])) {
    $clientClassesOutputFolder = $params['clientClassesOutputFolder'];
}
if (isset($params['authenticationType'])) {
    $authenticationType = $params['authenticationType'];
}
if (isset($params['username'])) {
    $username = $params['username'];
}
if (isset($params['password'])) {
    $password = $params['password'];
}
if (isset($params['connectionTimeout'])) {
    $connectionTimeout = $params['connectionTimeout'];
}

$flagGenerate = true;
if (empty($serviceWsdlUrl) || empty($clientClassesNamespace) || empty($clientClassesOutputFolder)) {
    $flagGenerate = false;
    if (empty($serviceWsdlUrl)) {
        echo "SOAP service WSDL url was not provided";
    }
    if (empty($clientClassesNamespace)) {
        echo "PHP client classes namespace was not provided";
    }
    if (empty($clientClassesOutputFolder)) {
        echo "PHP client classes output folder was not provided";
    }
}

if ($flagGenerate) {
    $generator = new Evertracker\SoapClientGenerator\GeneratorManager($serviceWsdlUrl, $clientClassesNamespace, $clientClassesOutputFolder);
    if (!is_null($authenticationType)) {
        $generator->setAuthenticationType($authenticationType);
        if (!is_null($username) && !is_null($password)) {
            $generator->setCredentials($username, $password);
        }
    }
    if (!is_null($connectionTimeout)) {
        $generator->setConnectionTimeout($connectionTimeout);
    }
    $generator->generate();
}
