<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Model;

use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Series;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    /**
     * @dataProvider articleDataProvider
     */
    public function testArticleWithCorrectData(?Series $series, ?int $seriesPart, bool $archived, ?string $preview): void
    {
        $article = new Article(
            slug: '',
            category: new Category(
                slug: '',
                title: '',
                filePath: '',
            ),
            series: $series,
            seriesPart: $seriesPart,
            archived: $archived,
            author: '',
            published: new DateTimeImmutable(),
            title: '',
            preview: $preview,
            content: '',
            filePath: '',
        );

        $this->assertInstanceOf(Article::class, $article);
    }

    public static function articleDataProvider(): array
    {
        return [
            'null' => [
                'series' => null,
                'seriesPart' => null,
                'archived' => false,
                'preview' => null,
            ],
            'text' => [
                'series' => new Series(
                    slug: '',
                    title: '',
                    pretext: null,
                    parts: -1,
                    filePath: '',
                ),
                'seriesPart' => -1,
                'archived' => true,
                'preview' => '',
            ],
        ];
    }
}