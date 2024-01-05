<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model;

final readonly class Series
{
    public function __construct(
        public string $slug,
        public string $title,
        public ?string $pretext,
        public int $parts,
        public string $filePath,
    ) {
    }
}