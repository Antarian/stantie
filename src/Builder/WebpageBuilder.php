<?php
declare(strict_types=1);
namespace Antarian\Stantie\Builder;

use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Series;
use Antarian\Stantie\Model\SeriesNav;
use Twig\Environment;

final class WebpageBuilder
{
    /** @var array|Category[] */
    private array $categories;

    /** @var array|Series[] */
    private array $series;

    /** @var array|Article[] */
    private array $recentArticles;

    /** @var array|Article[] */
    private array $archivedArticles;

    public function __construct(
        private Environment $twig,
        private ContentBuilder $contentBuilder,
        private string $targetPath,
    ) {
        $this->categories = $this->contentBuilder->getCategoryList();
        $this->series = $this->contentBuilder->getSeriesList();
        $this->recentArticles = $this->contentBuilder->getNewestArticles(5);
        $this->archivedArticles = $this->contentBuilder->getArchivedArticles(5);
    }

    public function __invoke()
    {
        $this->generateBlogPage();
        $this->generateCategoryPages();
        $this->generateSeriesPages();
        $this->generateArticleDetailPages();
        $this->generateArchivedPage();
        $this->generateAboutPage();
    }

    private function generateBlogPage(): void
    {
        $articleList = $this->contentBuilder->getNewestArticles();

        $htmlContent = $this->twig->render('pages/list.html.twig', [
                'mainPage' => 'blog',
                'articleList' => $articleList,
                'categories' => $this->categories,
                'series' => $this->series,
                'recentArticles' => $this->recentArticles,
                'archivedArticles' => $this->archivedArticles,
            ]);

        file_put_contents($this->targetPath . 'index.html', $htmlContent);
    }

    private function generateCategoryPages(): void
    {
        foreach ($this->categories as $category) {
            $articleList = $this->contentBuilder->getArticlesForCategory($category->slug);
            $htmlContent = $this->twig->render('pages/list.html.twig', [
                'mainPage' => 'blog',
                'articleList' => $articleList,
                'category' => $category,
                'categories' => $this->categories,
                'series' => $this->series,
                'recentArticles' => $this->recentArticles,
                'archivedArticles' => $this->archivedArticles,
            ]);

            file_put_contents($this->targetPath . $category->filePath, $htmlContent);
        }
    }

    private function generateSeriesPages(): void
    {
        foreach ($this->series as $seriesDetails) {
            $articleList = $this->contentBuilder->getArticlesForSeries($seriesDetails->slug);

            $htmlContent = $this->twig->render('pages/list.html.twig', [
                'mainPage' => 'blog',
                'articleList' => $articleList,
                'seriesDetails' => $seriesDetails,
                'categories' => $this->categories,
                'series' => $this->series,
                'recentArticles' => $this->recentArticles,
                'archivedArticles' => $this->archivedArticles,
            ]);

            file_put_contents($this->targetPath . $seriesDetails->filePath, $htmlContent);
        }
    }

    private function generateArchivedPage(): void
    {
        $articleList = $this->contentBuilder->getArchivedArticles(10);

        $htmlContent = $this->twig->render('pages/list.html.twig', [
            'mainPage' => 'blog',
            'articleList' => $articleList,
            'categories' => $this->categories,
            'series' => $this->series,
            'recentArticles' => $this->recentArticles,
            'archivedArticles' => $this->archivedArticles,
        ]);

        file_put_contents($this->targetPath . 'archived.html', $htmlContent);
    }

    private function generateArticleDetailPages(): void
    {
        $articleList = $this->contentBuilder->getArticles();

        foreach ($articleList as $article) {
            $seriesNav = null;
            if ($article->series) {
                $seriesNav = $this->getSeriesNav($article, $article->series);
            }

            $htmlContent = $this->twig->render('pages/article.html.twig', [
                'article' => $article,
                'seriesNav' => $seriesNav,
            ]);
            if (!is_dir($this->targetPath . '/article/')) {
                mkdir($this->targetPath . '/article/');
            }

            file_put_contents($this->targetPath . $article->filePath, $htmlContent);
        }
    }

    private function generateAboutPage(): void
    {
        $htmlContent = $this->twig->render('pages/about-me.html.twig', [
            'mainPage' => 'about-me',
        ]);

        file_put_contents($this->targetPath . 'about-me.html', $htmlContent);
    }

    private function getSeriesNav(Article $article, ?Series $seriesDetails): ?SeriesNav
    {
        if ($seriesDetails) {
            $previousArticle = null;
            $nextArticle = null;

            if ($article->seriesPart > 1) {
                $previousArticle = $this->contentBuilder->getArticlesInSeries($seriesDetails->slug, $article->seriesPart - 1);
            }

            if ($article->seriesPart < $seriesDetails->parts) {
                $nextArticle = $this->contentBuilder->getArticlesInSeries($seriesDetails->slug, $article->seriesPart + 1);
            }

            return new SeriesNav(
                previousArticle: $previousArticle,
                nextArticle: $nextArticle,
            );
        }

        return null;
    }
}