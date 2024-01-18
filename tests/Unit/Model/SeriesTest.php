<?php
declare(strict_types=1);
namespace Antarian\Tests\Unit\Model;

use Antarian\Stantie\Model\Series;
use PHPUnit\Framework\TestCase;

class SeriesTest extends TestCase
{
    /**
     * @dataProvider seriesDataProvider
     */
    public function testSeriesWithCorrectData(?string $pretext): void
    {
        $series = new Series(
            slug: '',
            title: '',
            pretext: $pretext,
            parts: 0,
            filePath: '',
        );

        $this->assertInstanceOf(Series::class, $series);
    }

    public static function seriesDataProvider(): array
    {
        return [
            'null' => [
                'pretext' => null,
            ],
            'text' => [
                'pretext' => '',
            ],
        ];
    }
}