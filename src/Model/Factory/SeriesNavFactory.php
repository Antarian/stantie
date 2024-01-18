<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model\Factory;

use Antarian\Stantie\Exception\ValidationException;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\SeriesNav;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SeriesNavFactory
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function create(
        ?Article $previousArticle,
        ?Article $nextArticle,
    ): SeriesNav {
        $seriesNav = new SeriesNav(
            previousArticle: $previousArticle,
            nextArticle: $nextArticle,
        );

        $violations = $this->validator->validate($seriesNav);

        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                $violationMessages[] = $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }

        return $seriesNav;
    }
}