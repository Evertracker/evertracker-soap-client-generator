<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 07.12.18
 * Time: 16:59
 */

namespace integration;


use Evertracker\SoapClientGenerator\GeneratorManager;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class SoapClientGenerationTest extends TestCase
{
    /** @var GeneratorManager */
    private $generatorManager;
    /** @var string */
    private $wsdlUrl;
    /** @var string */
    private $namespace;
    /** @var string */
    private $outputFolder;

    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $testFolder = __DIR__ . DIRECTORY_SEPARATOR;
        $this->wsdlUrl = $testFolder . "Service.wsdl";
        $this->namespace = "Evertracker\\Calculator";
        $this->outputFolder = $testFolder . "output";

        if (!file_exists($this->outputFolder)) {
            mkdir($this->outputFolder, 0777, true);
        }

        $this->generatorManager = new GeneratorManager($this->wsdlUrl, $this->namespace, $this->outputFolder);
    }

    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown();
        if (file_exists($this->outputFolder)) {
            $this->removeDirectory($this->outputFolder);
        }
    }

    private function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);

        return;
    }

    /**
     * @covers \Evertracker\SoapClientGenerator\GeneratorManager::generate()
     */
    public function testClientGeneration()
    {
        /** @var SplFileInfo $fileinfo */

        $expectedFileNames = ['Add.php', 'AddResponse.php', 'autoload.php', 'CalculatorService.php', 'Subtract.php', 'SubtractResponse.php'];


        $this->generatorManager->setConnectionTimeout(30);
        $this->generatorManager->setAuthenticationType(SOAP_AUTHENTICATION_BASIC);
        $this->generatorManager->setCredentials("username", "password");
        $this->generatorManager->generate();
        $fi = new FilesystemIterator($this->outputFolder, FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_FILEINFO);
        $this->assertEquals(count($expectedFileNames), iterator_count($fi));
        foreach ($fi as $fileinfo) {
            $this->assertTrue(in_array($fileinfo->getFilename(), $expectedFileNames));
        }

        $calculatorServiceFileName = $this->outputFolder . DIRECTORY_SEPARATOR . 'CalculatorService.php';
        $fileContents = file_get_contents($calculatorServiceFileName);
        $this->assertNotFalse(strpos($fileContents,"'connection_timeout' => 30,"));
        $this->assertNotFalse(strpos($fileContents,"'authentication' => 0,"));
        $this->assertNotFalse(strpos($fileContents,"'login' => 'username',"));
        $this->assertNotFalse(strpos($fileContents,"'password' => 'password',"));
        $this->assertNotFalse(strpos($fileContents,'public function Add(Add $parameters)'));
        $this->assertNotFalse(strpos($fileContents,'public function Subtract(Subtract $parameters)'));
    }
}