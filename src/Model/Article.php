<?php
declare(strict_types=1);

namespace Antarian\Stantie\Model;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Article
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex(pattern: '/^[a-z0-9-_]+$/')]
        public string $slug,

        #[Assert\Valid]
        public Category $category,

        #[Assert\Valid]
        public ?Series $series,

        #[Assert\GreaterThan(0)]
        public ?int $seriesPart,

        public bool $archived,

        #[Assert\NotBlank]
        public string $author,

        #[Assert\LessThanOrEqual('today')]
        public DateTimeImmutable $published,

        #[Assert\NotBlank]
        public string $title,

        #[Assert\NotBlank(allowNull: true)]
        public ?string $preview,

        #[Assert\NotBlank]
        public string $content,

        #[Assert\NotBlank]
        #[Assert\Regex(pattern: '/^[a-z0-9-_\/\.]+$/')]
        public string $filePath,
    ) {
    }
}