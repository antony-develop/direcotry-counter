<?php

declare(strict_types=1);

namespace App\Tests;


use App\CounterService;
use PHPUnit\Framework\TestCase;

class CounterServiceTest extends TestCase
{
    private const FILES_ROOT_DIR = '/tmp/directory_counter_test_root';
    private const COUNTER_FILE_NAME = 'count';

    /**
     * @var CounterService
     */
    private $counterService;

    public function testCorrectParameters(): void
    {
        $count = $this->counterService->getCountByFileInPath(
            self::COUNTER_FILE_NAME,
            self::FILES_ROOT_DIR,
            );

        $this->assertEquals($count, 18);
    }

    public function testIncorrectFileNameParameters(): void
    {
        $count = $this->counterService->getCountByFileInPath(
            self::COUNTER_FILE_NAME . date('c'),
            self::FILES_ROOT_DIR,
            );

        $this->assertEquals($count, 0);
    }

    public function testIncorrectDirectoryParameters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $count = $this->counterService->getCountByFileInPath(
            self::COUNTER_FILE_NAME,
            self::FILES_ROOT_DIR . date('c'),
            );
    }

    protected function setUp(): void
    {
        $this->createFiles();
        $this->counterService = new CounterService();
    }

    protected function tearDown(): void
    {
        $this->deleteDir(self::FILES_ROOT_DIR);
    }

    private function createFiles(): void
    {
        if (is_dir(self::FILES_ROOT_DIR)) {
            $this->deleteDir(self::FILES_ROOT_DIR);
        }

        $rootDir = self::FILES_ROOT_DIR;
        $dir1 = "$rootDir/dir1";
        $dir2 = "$rootDir/dir2";
        $dir2_1 = "$dir2/dir2.1";

        mkdir($rootDir);
        mkdir($dir1);
        mkdir($dir2);
        mkdir($dir2_1);

        file_put_contents("$rootDir/" . self::COUNTER_FILE_NAME , "1\n2\n3");
        file_put_contents("$dir2/" . self::COUNTER_FILE_NAME , "1 dsfgdsg 2 3");
        file_put_contents("$dir2_1/" . self::COUNTER_FILE_NAME , "1\n2\n3");
    }

    private function deleteDir(string $path): void
    {
        system('rm -r ' . escapeshellarg($path));
    }
}
