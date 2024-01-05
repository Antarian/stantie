<?php
declare(strict_types=1);
namespace Antarian\Stantie\FileSystem;

use Iterator;
use Symfony\Component\Finder\Finder as SymfonyFinder;
use Symfony\Component\Finder\SplFileInfo;

final class Finder
{
    public function __construct(
        private string $dataPath,
    ) {
    }

    /**
     * @param string $fileName filename or pattern (e.g. *-ending.md))
     * @param string $filePath path to directory or parent directory to search for the file
     * @return SplFileInfo|null
     */
    public function getFile(string $fileName, string $filePath = ''): ?SplFileInfo
    {
        $files = SymfonyFinder::create()->files()->name($fileName)->in($this->dataPath . $filePath);
        foreach ($files as $file) {
            return $file;
        }

        return null;
    }

    /**
     * @param string $fileName filename or pattern (e.g. *-ending.md))
     * @param string $filePath path to directory or parent directory to search for the files
     * @return Iterator<string, SplFileInfo>
     */
    public function getFiles(string $fileName, string $filePath): Iterator
    {
        return SymfonyFinder::create()->files()->name($fileName)->in($this->dataPath . $filePath)->getIterator();
    }
}