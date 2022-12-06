<?php

namespace PhpUniter\PhpUniterRequester\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PhpUniter\PhpUniterRequester\Report;
use PhpUniter\PhpUniterRequester\Requester;
use PhpUniter\PhpUniterRequester\RequesterFactory;
use PhpUniter\PhpUniterRequester\Tests\CreatesApplicationPackage;

/**
 * todo: change to remote request test.
 *
 * @property Report $report
 */
class RegisterLocalTest extends TestCase
{
    use CreatesApplicationPackage;

    public array $container = [];
    private string $pathToTest;
    private string $projectRoot;

    public function setUp(): void
    {
        parent::setUp();
        $this->conf = self::getConfig();
        $this->pathToTest = (string) $this->conf['unitTestsDirectory'];
        $this->projectRoot = $this->conf['basePath'];
        $this->report = new Report();
    }

    public function testGenerate()
    {
        $registerService = RequesterFactory::registerServiceFactory($this->conf);
        $phpUnitService = RequesterFactory::generateServiceFactory($this->conf);
        $requester = new Requester($registerService, $phpUnitService, $this->projectRoot);
        $code = $requester->generate(__DIR__.'/Application/Obfuscator/Entity/Fixtures/SourceClass.php.input', $this->projectRoot);

        self::assertEquals(0, $code);
    }

    public function testRegister()
    {
        $registerService = RequesterFactory::registerServiceFactory($this->conf);
        $phpUnitService = RequesterFactory::generateServiceFactory($this->conf);
        $requester = new Requester($registerService, $phpUnitService, $this->projectRoot);
        $code = $requester->register(
            'a'.uniqid().'@test.ru',
            'NewMockery0',
        );

        self::assertEquals(0, $code);
    }
}
