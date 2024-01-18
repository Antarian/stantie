<?php
declare(strict_types=1);
namespace Antarian\Stantie\Builder;

use Antarian\Stantie\FileSystem\FileExtractor;
use Antarian\Stantie\FileSystem\Finder;
use Antarian\Stantie\Filter\Filter;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Factory\ArticleFactory;
use Antarian\Stantie\Model\Factory\CategoryFactory;
use Antarian\Stantie\Model\Factory\MetadataFactory;
use Antarian\Stantie\Model\Factory\SeriesFactory;
use Antarian\Stantie\Model\Factory\SeriesNavFactory;
use Antarian\Stantie\Model\Metadata;
use Antarian\Stantie\Model\Series;
use Antarian\Stantie\Sorter\Sorter;

final class ContentBuilder
{
    public function __construct(
        private Finder $finder,
        private FileExtractor $fileExtractor,
        private ArticleFactory $articleFactory,
        private MetadataFactory $metadataFactory,
        private CategoryFactory $categoryFactory,
        private SeriesFactory $seriesFactory,
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

            $categories[$categoryData['slug']] = $this->categoryFactory->create(
                slug: $categoryData['slug'],
                title: $categoryData['title'],
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
            $series[$data['slug']] = $this->seriesFactory->create(
                slug: $data['slug'],
                title: $data['title'],
                pretext: $data['pretext'],
                parts: $data['parts'],
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
            $metadata = $this->metadataFactory->createFromArray($metadata);
            $seriesDetail = $metadata->seriesSlug ? $this->getSeriesList()[$metadata->seriesSlug] : null;

            $articles[] = $this->articleFactory->create(
                slug: $metadata->slug,
                category: $this->getCategoryList()[$metadata->categorySlug],
                archived: $metadata->archived,
                author: $metadata->author,
                published: $metadata->published,
                title: $metadata->title,
                content: $htmlContent,
                series: $seriesDetail,
                seriesPart: $metadata->seriesPart,
                preview: $metadata->preview,
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