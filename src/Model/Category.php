<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model;

final readonly class Category
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $filePath,
    ) {
    }
}