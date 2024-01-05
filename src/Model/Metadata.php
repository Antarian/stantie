<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

final readonly class Metadata
{
    public static function fromArray(array $data): self
    {
        $published = DateTimeImmutable::createFromFormat('jS M Y', $data['published']);
        Assert::same($published->format('jS M Y'), $data['published']);

        return new self(
            title: $data['title'],
            preview: $data['preview'],
            slug: $data['slug'],
            categorySlug: $data['categorySlug'],
            seriesSlug: $data['seriesSlug'],
            seriesPart: $data['seriesPart'],
            author: $data['author'],
            published: DateTimeImmutable::createFromFormat('jS M Y', $data['published']),
            archived: $data['archived'],
        );
    }

    private function __construct(
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
        Assert::notEmpty($this->title);
        Assert::nullOrNotEmpty($this->preview);
        Assert::regex($this->slug, '/^[a-z0-9-_]+$/');
        Assert::regex($this->categorySlug, '/^[a-z0-9-_]+$/');
        Assert::nullOrRegex($this->seriesSlug, '/^[a-z0-9-_]+$/');
        Assert::nullOrPositiveInteger($this->seriesPart);
        Assert::notEmpty($this->author);
    }
}