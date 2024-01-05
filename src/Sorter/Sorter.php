<?php
declare(strict_types=1);
namespace Antarian\Stantie\Sorter;

use Antarian\Stantie\Model\Article;

class Sorter
{
    /**
     * @param array|Article[] $articles
     * @return array|Article[]
     */
    public function sortArticlesByDateAndNameDesc(array $articles): array
    {
        usort($articles, function (Article $article1, Article $article2) {
            if ($article1->published == $article2->published) {
                return strcmp(strtolower($article1->title), strtolower($article2->title)) * -1;
            }

            return $article1->published > $article2->published ? -1 : 1;
        });

        return $articles;
    }
}