<?php
declare(strict_types=1);
namespace Antarian\Stantie\Filter;

use Antarian\Stantie\Model\Article;

final class Filter
{
    /**
     * @param array|Article[] $articles
     * @return array|Article[]
     */
    public function filterArticlesByCategory(array $articles, string $categorySlug): array
    {
        return array_filter($articles, function (Article $article) use ($categorySlug) {
            if ($article->category->slug === $categorySlug) {
                return true;
            }

            return false;
        });
    }

    /**
     * @param array|Article[] $articles
     * @return array|Article[]
     */
    public function filterArticlesByArchived(array $articles): array
    {
        return array_filter($articles, function (Article $article) {
            if ($article->archived === true) {
                return true;
            }

            return false;
        });
    }

    /**
     * @param array|Article[] $articles
     * @return array|Article[]
     */
    public function filterOutArchivedArticles(array $articles): array
    {
        return array_filter($articles, function (Article $article) {
            if ($article->archived === true) {
                return false;
            }

            return true;
        });
    }

    /**
     * @param array|Article[] $articles
     * @return array|Article[]
     */
    public function filterArticlesBySeries(array $articles, string $seriesSlug): array
    {
        return array_filter($articles, function (Article $article) use ($seriesSlug) {
            if ($article->series && $article->series->slug === $seriesSlug) {
                return true;
            }

            return false;
        });
    }

    /**
     * @param array|Article[] $articles
     */
    public function filterArticleBySeriesAndPart(array $articles, string $seriesSlug, int $seriesPart): Article|false
    {
        $seriesArticle = array_filter($articles, function (Article $article) use ($seriesSlug, $seriesPart) {
            if ($article->series && $article->series->slug === $seriesSlug && $article->seriesPart === $seriesPart) {
                return true;
            }

            return false;
        });

        return reset($seriesArticle);
    }
}