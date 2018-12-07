<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 07.12.18
 * Time: 16:46
 */

use Wsdl2PhpGenerator\Generator;
use Evertracker\SoapClientGenerator\GeneratorManager;
use PHPUnit\Framework\TestCase;

class GeneratorManagerTest extends TestCase
{
    /** @var GeneratorManager */
    private $generatorManager;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        /** @var Generator $generatorMock */

        parent::setUp();
        $generatorMock = $this->createMock(Generator::class);
        $this->generatorManager = new GeneratorManager("http://server/service?wsdl", "namespace", "folder1/folder2");
        $this->generatorManager->setGenerator($generatorMock);
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::generate()
     */
    public function testGenerate()
    {
        $this->generatorManager->generate();
        $this->assertTrue(true);
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::setGenerator()
     */
    public function testSetGenerator()
    {
        $this->assertTrue(true);
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::setAuthenticationType()
     */
    public function testSetAuthenticationType()
    {
        $this->generatorManager->setAuthenticationType(SOAP_AUTHENTICATION_BASIC);
        $this->generatorManager->setAuthenticationType(SOAP_AUTHENTICATION_BASIC);
        $this->expectException(InvalidArgumentException::class);
        $this->generatorManager->setAuthenticationType(-1);
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::setConnectionTimeout()
     */
    public function testSetConnectionTimeout()
    {
        $this->generatorManager->setConnectionTimeout(5);
        $this->assertTrue(true);
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::setCredentials()
     */
    public function testSetCredentials()
    {
        $this->generatorManager->setCredentials("username", "password");
        $this->assertTrue(true);
    }
}
