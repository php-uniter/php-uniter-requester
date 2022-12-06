<?php

namespace PhpUniter\PhpUniterRequester;

use GuzzleHttp\Exception\GuzzleException;
use PhpUniter\PhpUniterRequester\Application\File\Exception\FileNotAccessed;
use PhpUniter\PhpUniterRequester\Application\Obfuscator\ObfuscatorFabric;
use PhpUniter\PhpUniterRequester\Application\PhpUniter\Entity\PhpUnitTest;
use PhpUniter\PhpUniterRequester\Application\PhpUnitService;
use PhpUniter\PhpUniterRequester\Application\PhpUnitUserRegisterService;

class Requester
{
    public PhpUnitService $phpUnitService;
    public ObfuscatorFabric $obfuscatorFabric;
    public PhpUnitUserRegisterService $registerService;

    private Report $report;
    private PhpUnitTest $phpUnitTest;
    private string $basePath;

    public function __construct(PhpUnitUserRegisterService $registerService, PhpUnitService $phpUnitService, string $basePath)
    {
        $this->report = new Report();
        $this->obfuscatorFabric = new ObfuscatorFabric();
        $this->registerService = $registerService;
        $this->phpUnitService = $phpUnitService;
        $this->basePath = $basePath;
    }

    public function generate(string $filePath): int
    {
        try {
            chdir($this->basePath);

            if (!is_readable($filePath)) {
                throw new FileNotAccessed("File $filePath was not found");
            }

            try {
                $localFile = $this->obfuscatorFabric->createFile($filePath);
                $this->phpUnitTest = $this->phpUnitService->process($localFile, $this->obfuscatorFabric);
                $this->report->info('Generated test was written to '.$this->phpUnitTest->getPathToTest());
            } catch (GuzzleException $e) {
                $this->report->error($e->getMessage());

                return 1;
            }
        } catch (\Throwable $e) {
            $this->report->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    public function register(string $email, string $password): ?int
    {
        try {
            if ($this->registerService->process($email, $password)) {
                $this->report->info('User registered. Access token in your email. Put it in .env file - PHP_UNITER_ACCESS_TOKEN');
            }
        } catch (GuzzleException $e) {
            $this->report->error($e->getMessage());

            return 1;
        } catch (\Throwable $e) {
            $this->report->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    public function getPhpUnitTest(): PhpUnitTest
    {
        return $this->phpUnitTest;
    }

    /**
     * @return Report
     */
    public function getReport(): Report
    {
        return $this->report;
    }
}
