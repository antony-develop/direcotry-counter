<?php

declare(strict_types=1);

namespace App;

class CounterService
{
    public function getCountByFileInPath(string $fileName, string $path): int
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException("Can't open directory - $path");
        }

        $files = $this->getFiles($fileName, $path);

        return $this->getFilesCount($files);
    }

    /**
     * @param string $fileName
     * @param string $path
     *
     * @return string[]
     */
    private function getFiles(string $fileName, string $path): array
    {
        $files = [];
        $dirIterator = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($dirIterator);

        foreach ($iterator as $filePath => $fileInfo) {
            if ($fileInfo->isFile() && $fileInfo->getFileName() === $fileName) {
                $files[] = $filePath;
            }
        }

        return $files;
    }

    /**
     * @param string[] $files
     *
     * @return int
     */
    private function getFilesCount(array $files): int
    {
        $count = 0;

        foreach ($files as $file) {
            $count += $this->getFileCount($file);
        }

        return $count;
    }

    private function getFileCount(string $path): int
    {
        preg_match_all('/\d/', file_get_contents($path), $matches);

        return array_sum($matches[0]);
    }
}
