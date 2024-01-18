<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Sorter;

use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Sorter\Sorter;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    /**
     * @dataProvider sortArticlesByDateProvider
     */
    public function testSortArticlesByDate(array $articles, array $expected): void
    {
        $sorter = new Sorter();
        $result = $sorter->sortArticlesByDateAndNameDesc($articles);

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider sortArticlesByNameProvider
     */
    public function testSortArticlesByName(array $articles, array $expected): void
    {
        $sorter = new Sorter();
        $result = $sorter->sortArticlesByDateAndNameDesc($articles);

        $this->assertEquals($expected, $result);
    }

    public static function sortArticlesByDateProvider(): array
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
            published: DateTimeImmutable::createFromFormat('Y-m-d', '2024-01-16'),
            title: 'A',
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
            published: DateTimeImmutable::createFromFormat('Y-m-d', '2024-01-17'),
            title: 'A',
            preview: null,
            content: '',
            filePath: ''
        );

        return [
            'sort-by-date' => [
                'articles' => [
                    $a,
                    $b,
                ],
                'expected' => [
                    $b,
                    $a,
                ],
            ],
        ];
    }

    public static function sortArticlesByNameProvider(): array
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
            published: DateTimeImmutable::createFromFormat('Y-m-d', '2024-01-16'),
            title: 'A',
            preview: null,
            content: '',
            filePath: ''
        );

        $b = new Article(
            slug: 'a',
            category: new Category(
                slug: 'a',
                title: 'A',
                filePath: 'a'
            ),
            series: null,
            seriesPart: null,
            archived: false,
            author: 'a',
            published: DateTimeImmutable::createFromFormat('Y-m-d', '2024-01-16'),
            title: 'B',
            preview: null,
            content: 'a',
            filePath: 'a'
        );

        return [
            'sort-by-date' => [
                'articles' => [
                    $a,
                    $b,
                ],
                'expected' => [
                    $b,
                    $a,
                ],
            ],
        ];
    }
}