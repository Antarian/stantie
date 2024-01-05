<?php
declare(strict_types=1);
namespace Antarian\Stantie\Builder;

use Antarian\Stantie\FileSystem\FileExtractor;
use Antarian\Stantie\FileSystem\Finder;
use Antarian\Stantie\Filter\Filter;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Metadata;
use Antarian\Stantie\Model\Series;
use Antarian\Stantie\Sorter\Sorter;

final class ContentBuilder
{
    public function __construct(
        private Finder $finder,
        private FileExtractor $fileExtractor,
        private Filter $filter,
        private Sorter $sorter,
    ) {
    }

    /**
     * @return array|Category[]
     */
    public function getCategoryList(): array
    {
        $categoriesFile = $this->finder->getFile('categories.yaml');
        list('categories' => $categoriesData) = $this->fileExtractor->getYamlFileContent($categoriesFile);

        $categories = [];
        foreach ($categoriesData as $categoryData) {

            $categories[$categoryData['slug']] = new Category(
                slug: $categoryData['slug'],
                title: $categoryData['title'],
                filePath: 'category-' . $categoryData['slug'] . '.html',
            );
        }

        return $categories;
    }

    /**
     * @return array|Series[]
     */
    public function getSeriesList(): array
    {
        $seriesFile = $this->finder->getFile('series.yaml');
        list('series' => $seriesData) = $this->fileExtractor->getYamlFileContent($seriesFile);

        $series = [];
        foreach ($seriesData as $data) {
            $series[$data['slug']] = new Series(
                slug: $data['slug'],
                title: $data['title'],
                pretext: $data['pretext'],
                parts: $data['parts'],
                filePath: 'series-' . $data['slug'] . '.html',
            );
        }

        return $series;
    }

    /**
     * @return array|Article[]
     */
    public function getArticles(): array
    {
        $articleFiles = $this->finder->getFiles(fileName: '*.md', filePath: 'articles');

        $articlesData = [];
        foreach ($articleFiles as $articleFile) {
            $articlesData[] = $this->fileExtractor->getMdFileContent($articleFile);
        }

        $articles = [];
        foreach ($articlesData as ['metadata' => $metadata, 'htmlContent' => $htmlContent]) {
            $metadata = Metadata::fromArray($metadata);
            $filePath = 'article/' . $metadata->slug . '.html';
            $seriesDetail = $metadata->seriesSlug ? $this->getSeriesList()[$metadata->seriesSlug] : null;
            if ($seriesDetail) {
                $filePath = 'article/' . $seriesDetail->slug . '-part-' . $metadata->seriesPart . '-' . $metadata->slug . '.html';
            }

            $articles[] = new Article(
                slug: $metadata->slug,
                category: $this->getCategoryList()[$metadata->categorySlug],
                series: $seriesDetail,
                seriesPart: $metadata->seriesPart,
                archived: $metadata->archived,
                author: $metadata->author,
                published: $metadata->published,
                title: $metadata->title,
                preview: $metadata->preview,
                content: $htmlContent,
                filePath: $filePath,
            );
        }

        return $articles;
    }

    public function getNewestArticles(int $amount = 10)
    {
        $articles = $this->getArticles();
        $articles = $this->filter->filterOutArchivedArticles($articles);
        $articles = $this->sorter->sortArticlesByDateAndNameDesc($articles);

        return array_slice($articles, 0, $amount);
    }

    public function getArticlesForCategory(string $categorySlug)
    {
        $articles = $this->getArticles();
        $articles = $this->filter->filterArticlesByCategory($articles, $categorySlug);
        $articles = $this->sorter->sortArticlesByDateAndNameDesc($articles);

        return $articles;
    }

    public function getArchivedArticles($amount = 5)
    {
        $articles = $this->getArticles();
        $articles = $this->filter->filterArticlesByArchived($articles);
        $articles = $this->sorter->sortArticlesByDateAndNameDesc($articles);

        return array_slice($articles, 0, $amount);
    }

    public function getArticlesForSeries(string $seriesSlug)
    {
        $articles = $this->getArticles();
        $articles = $this->filter->filterArticlesBySeries($articles, $seriesSlug);
        $articles = $this->sorter->sortArticlesByDateAndNameDesc($articles);

        return $articles;
    }

    public function getArticlesInSeries(string $seriesSlug, int $seriesPart): Article
    {
        $articles = $this->getArticles();
        $article = $this->filter->filterArticleBySeriesAndPart($articles, $seriesSlug, $seriesPart);

        return $article;
    }
}