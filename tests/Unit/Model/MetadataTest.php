<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Model;

use Antarian\Stantie\Model\Metadata;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
    /**
     * @dataProvider metadataDataProvider
     */
    public function testMetadataWithCorrectData(?string $textFields, ?int $seriesPart, bool $archived): void
    {
        $metadata = new Metadata(
            title: '',
            preview: $textFields,
            slug: '',
            categorySlug: '',
            seriesSlug: $textFields,
            seriesPart: $seriesPart,
            author: '',
            published: new DateTimeImmutable(),
            archived: $archived,
        );

        $this->assertInstanceOf(Metadata::class, $metadata);
    }

    public static function metadataDataProvider(): array
    {
        return [
            'null' => [
                'textFields' => null,
                'seriesPart' => 0,
                'archived' => false,
            ],
            'text' => [
                'textFields' => '',
                'seriesPart' => -1,
                'archived' => true,
            ],
        ];
    }

}