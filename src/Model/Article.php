<?php
declare(strict_types=1);

namespace Antarian\Stantie\Model;

use DateTimeImmutable;

final readonly class Article
{
    public function __construct(
        public string $slug,
        public Category $category,
        public ?Series $series,
        public ?int $seriesPart,
        public bool $archived,
        public string $author,
        public DateTimeImmutable $published,
        public string $title,
        public ?string $preview,
        public string $content,
        public string $filePath,
    ) {
    }
}