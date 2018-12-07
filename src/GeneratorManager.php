<?php

namespace Evertracker\SoapClientGenerator;

use InvalidArgumentException;
use Wsdl2PhpGenerator\Generator;

/**
 * Class Generator
 * @package Evertracker\SoapClientGenerator
 */
class GeneratorManager
{
    /** @var Generator */
    private $generator;
    /** @var string URL of the service's WSDL file */
    private $serviceWsdlUrl;
    /** @var null string PHP namespace of the SOAP client classes */
    private $clientClassesNamespace;
    /** @var null string Folder path where the SOAP client classes will be created */
    private $clientClassesOutputFolder;
    /** @var int SOAP service authentication type; SOAP_AUTHENTICATION_BASIC or SOAP_AUTHENTICATION_DIGEST */
    private $authenticationType;
    /** @var string the default username for the HTTP BASIC authentication type for the SOAP client */
    private $username;
    /** @var string the default password for the HTTP BASIC authentication type for the SOAP client */
    private $password;
    /** @var int default timeout in seconds for the SOAP client */
    private $connectionTimeout;

    /**
     * Generator constructor.
     *
     * @param string $serviceWsdlUrl
     * @param string $clientClassesNamespace
     * @param string $clientClassesOutputFolder
     */
    public function __construct($serviceWsdlUrl, $clientClassesNamespace, $clientClassesOutputFolder)
    {
        $this->generator = null;
        $this->serviceWsdlUrl = $serviceWsdlUrl;
        $this->clientClassesNamespace = $clientClassesNamespace;
        $this->clientClassesOutputFolder = $clientClassesOutputFolder;
        $this->authenticationType = null;
        $this->username = null;
        $this->password = null;
        $this->connectionTimeout = 60;
    }

    /**
     * @param Generator $generator
     */
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return Generator
     */
    private function getGenerator()
    {
        if (is_null($this->generator)) {
            $this->generator = new Generator();
        }

        return $this->generator;
    }


    /**
     * @param int $authenticationType SOAP service authentication type; SOAP_AUTHENTICATION_BASIC or
     *                                SOAP_AUTHENTICATION_DIGEST
     */
    public function setAuthenticationType($authenticationType)
    {
        $authenticationTypeInt = intval($authenticationType);
        if ($authenticationTypeInt !== SOAP_AUTHENTICATION_BASIC && $authenticationTypeInt !== SOAP_AUTHENTICATION_DIGEST) {
            throw new InvalidArgumentException("Unsupported authentication type: {$authenticationType}");
        }
        $this->authenticationType = $authenticationTypeInt;
    }

    /**
     * Sets credentials for the HTTP BASIC authentication for the SOAP service
     *
     * @param string $username
     * @param string $password
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param int $connectionTimeout timeout in seconds for the SOAP client connection
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        $this->connectionTimeout = intval($connectionTimeout);
    }

    /**
     * Generates the PHP client classes for the SOAP web service
     */
    public function generate()
    {
        $soapClientOptions = $this->getSoapClientOptions();
        $config = $this->getGeneratorConfig($soapClientOptions);
        $generator = $this->getGenerator();
        $generator->generate($config);
    }

    /**
     * Gets the configuration for the Wsdl2PhpGenerator
     *
     * @param array $soapClientOptions
     *
     * @return \Wsdl2PhpGenerator\Config
     */
    private function getGeneratorConfig(array $soapClientOptions)
    {
        $params = [];
        $params['inputFile'] = $this->serviceWsdlUrl;
        $params['outputDir'] = $this->clientClassesOutputFolder;
        $params['namespaceName'] = $this->clientClassesNamespace;
        $params['soapClientOptions'] = $soapClientOptions;

        $config = new \Wsdl2PhpGenerator\Config($params);

        return $config;
    }

    /**
     * Gets the options for the SOAP client
     * @return array
     */
    private function getSoapClientOptions()
    {
        $soapClientOptions = [];
        $soapClientOptions['connection_timeout'] = $this->connectionTimeout;
        if (!is_null($this->authenticationType)) {
            if ($this->authenticationType == SOAP_AUTHENTICATION_DIGEST) {
                throw new InvalidArgumentException("SOAP_AUTHENTICATION_DIGEST authentication type is not implemented yet!");
            }
            $soapClientOptions['authentication'] = $this->authenticationType;
            $soapClientOptions['login'] = $this->username;
            $soapClientOptions['password'] = $this->password;
        }

        return $soapClientOptions;
    }
}