<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model\Factory;

use Antarian\Stantie\Exception\ValidationException;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Series;
use DateTimeImmutable;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ArticleFactory
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function create(
        string $slug,
        Category $category,
        bool $archived,
        string $author,
        DateTimeImmutable $published,
        string $title,
        string $content,
        ?Series $series = null,
        ?int $seriesPart = null,
        ?string $preview = null,
    ): Article {
        if (($series === null && $seriesPart !== null) || ($series !== null && $seriesPart === null)) {
            throw new ValidationException(sprintf('Please provide valid series data. Provided series \'%s\' and seriesPart \'%s\'', print_r($series, true), $seriesPart));
        }

        $filePath = 'article/' . $slug . '.html';
        if ($series) {
            $filePath = 'article/' . $series->slug . '-part-' . $seriesPart . '-' . $slug . '.html';
        }

        $article = new Article(
            slug: $slug,
            category: $category,
            series: $series,
            seriesPart: $seriesPart,
            archived: $archived,
            author: $author,
            published: $published,
            title: $title,
            preview: $preview,
            content: $content,
            filePath: $filePath,
        );

        $violations = $this->validator->validate($article);

        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                var_dump($violation);
                $violationMessages[] = $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }

        return $article;
    }
}