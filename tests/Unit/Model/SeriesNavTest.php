<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Model;

use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\SeriesNav;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SeriesNavTest extends TestCase
{
    /**
     * @dataProvider seriesNavDataProvider
     */
    public function testSeriesNavWithCorrectData(?Article $prevArticle, ?Article $nextArticle): void
    {
        $seriesNav = new SeriesNav(
            previousArticle: $prevArticle,
            nextArticle: $nextArticle,
        );

        $this->assertInstanceOf(SeriesNav::class, $seriesNav);
    }

    public static function seriesNavDataProvider(): array
    {
        $a = new Article(
            slug: '',
            category: new Category(
                slug: '',
                title: '',
                filePath: ''
            ),
            series: null,
            seriesPart: null,
            archived: false,
            author: '',
            published: new DateTimeImmutable(),
            title: '',
            preview: null,
            content: '',
            filePath: ''
        );

        $b = new Article(
            slug: '',
            category: new Category(
                slug: '',
                title: '',
                filePath: ''
            ),
            series: null,
            seriesPart: null,
            archived: false,
            author: '',
            published: new DateTimeImmutable(),
            title: '',
            preview: null,
            content: '',
            filePath: ''
        );

        return [
            'allnull' => [
                'prevArticle' => null,
                'nextArticle' => null,
            ],
            'firstNull' => [
                'prevArticle' => null,
                'nextArticle' => $b,
            ],
            'secondNull' => [
                'prevArticle' => $a,
                'nextArticle' => null,
            ],
            'nonull' => [
                'prevArticle' => $a,
                'nextArticle' => $b,
            ],
        ];
    }
}