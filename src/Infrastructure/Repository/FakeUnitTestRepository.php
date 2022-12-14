<?php

namespace PhpUniter\PhpUniterRequester\Infrastructure\Repository;

use PhpUniter\PhpUniterRequester\Application\PhpUniter\Entity\PhpUnitTest;

class FakeUnitTestRepository implements UnitTestRepositoryInterface
{
    private array $files = [];

    public function saveOne(PhpUnitTest $unitTest, string $relativePath, string $className): int
    {
        $this->files[$className] = $unitTest->getFinalUnitTest();

        return strlen($this->files[$className]);
    }

    public function getFile(string $className): string
    {
        return $this->files[$className];
    }
}
