<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model;

final readonly class SeriesNav
{
    public function __construct(
        public ?Article $previousArticle,
        public ?Article $nextArticle,
    ) {
    }
}