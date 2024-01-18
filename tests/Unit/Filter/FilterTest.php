<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Filter;

use Antarian\Stantie\Filter\Filter;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Series;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    private array $articles;

    protected function setUp(): void
    {
        $this->articles = [
            new Article(
                slug: '',
                category: new Category(slug: 'cat1', title: '', filePath: ''),
                series: null,
                seriesPart: null,
                archived: false,
                author: '',
                published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                title: '',
                preview: null,
                content: '',
                filePath: '',
            ),
            new Article(
                slug: '',
                category: new Category(slug: 'cat2', title: '', filePath: ''),
                series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                seriesPart: 1,
                archived: false,
                author: '',
                published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                title: '',
                preview: null,
                content: '',
                filePath: '',
            ),
            new Article(
                slug: '',
                category: new Category(slug: 'cat3', title: '', filePath: ''),
                series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                seriesPart: 2,
                archived: true,
                author: '',
                published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                title: '',
                preview: null,
                content: '',
                filePath: '',
            ),
        ];
    }

    /**
     * @dataProvider articlesByCategoryDataProvider
     */
    public function testFilterArticlesByCategory(string $categorySlug, array $expected): void
    {
        $filter = new Filter();
        $result = $filter->filterArticlesByCategory($this->articles, $categorySlug);

        $this->assertEquals($expected, $result);
    }


    /**
     * @dataProvider articlesByArchivedDataProvider
     */
    public function testFilterArticlesByArchived(array $expected): void
    {
        $filter = new Filter();
        $result = $filter->filterArticlesByArchived($this->articles);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider articlesNotArchivedDataProvider
     */
    public function testFilterOutArchivedArticles(array $expected): void
    {
        $filter = new Filter();
        $result = $filter->filterOutArchivedArticles($this->articles);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider articlesBySeriesDataProvider
     */
    public function testFilterArticlesBySeries(string $seriesSlug, array $expected): void
    {
        $filter = new Filter();
        $result = $filter->filterArticlesBySeries($this->articles, $seriesSlug);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider articleBySeriesAndSeriesPartDataProvider
     */
    public function testFilterArticleBySeriesAndPart(string $seriesSlug, int $seriesPart, Article $expected): void
    {
        $filter = new Filter();
        $result = $filter->filterArticleBySeriesAndPart($this->articles, $seriesSlug, $seriesPart);

        $this->assertEquals($expected, $result);
    }

    public static function articlesByCategoryDataProvider(): array
    {
        return [
            'categoryFilter' => [
                'categorySlug' => 'cat1',
                'expected' => [
                    0 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat1', title: '', filePath: ''),
                        series: null,
                        seriesPart: null,
                        archived: false,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                ],
            ],
        ];
    }

    public static function articlesByArchivedDataProvider(): array
    {
        return [
            'archivedFilter' => [
                'expected' => [
                    2 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat3', title: '', filePath: ''),
                        series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                        seriesPart: 2,
                        archived: true,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                ],
            ],
        ];
    }

    public static function articlesNotArchivedDataProvider(): array
    {
        return [
            'notArchivedFilter' => [
                'expected' => [
                    0 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat1', title: '', filePath: ''),
                        series: null,
                        seriesPart: null,
                        archived: false,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                    1 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat2', title: '', filePath: ''),
                        series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                        seriesPart: 1,
                        archived: false,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                ],
            ],
        ];
    }

    public static function articlesBySeriesDataProvider(): array
    {
        return [
            'categoryFilter' => [
                'seriesSlug' => 'series1',
                'expected' => [
                    1 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat2', title: '', filePath: ''),
                        series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                        seriesPart: 1,
                        archived: false,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                    2 => new Article(
                        slug: '',
                        category: new Category(slug: 'cat3', title: '', filePath: ''),
                        series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                        seriesPart: 2,
                        archived: true,
                        author: '',
                        published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                        title: '',
                        preview: null,
                        content: '',
                        filePath: '',
                    ),
                ],
            ],
        ];
    }

    public static function articleBySeriesAndSeriesPartDataProvider(): array
    {
        return [
            'categoryFilter' => [
                'seriesSlug' => 'series1',
                'seriesPart' => 2,
                'expected' => new Article(
                    slug: '',
                    category: new Category(slug: 'cat3', title: '', filePath: ''),
                    series: new Series(slug: 'series1', title: '', pretext: null, parts: 1, filePath: ''),
                    seriesPart: 2,
                    archived: true,
                    author: '',
                    published: DateTimeImmutable::createFromFormat('Y-m-d h:m:s', '2024-01-18 00:00:00'),
                    title: '',
                    preview: null,
                    content: '',
                    filePath: '',
                ),
            ],
        ];
    }
}