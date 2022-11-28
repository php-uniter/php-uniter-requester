<?php

namespace PhpUniter\Requester\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PhpUniter\External\Conf;
use PhpUniter\Requester\Tests\CreatesApplicationPackage;

class CommandFileWriteTest extends TestCase
{
    use CreatesApplicationPackage;

    public array $container = [];
    private string $pathToTest;
    private string $projectRoot;

    public function setUp(): void
    {
        parent::setUp();
        $conf = new Conf();
        $this->pathToTest = (string) $conf::get('unitTestsDirectory');
        $this->projectRoot = $conf::get('basePath');
    }

    public function testIsWritable()
    {
        self::assertIsWritable($this->projectRoot.'/'.$this->pathToTest);
    }
}