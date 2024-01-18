<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model;

use DateTimeImmutable;

final readonly class Metadata
{
    public function __construct(
        public string $title,
        public ?string $preview,
        public string $slug,
        public string $categorySlug,
        public ?string $seriesSlug,
        public ?int $seriesPart,
        public string $author,
        public DateTimeImmutable $published,
        public bool $archived = false,
    ) {
    }
}