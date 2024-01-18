<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model\Factory;

use Antarian\Stantie\Exception\ValidationException;
use Antarian\Stantie\Model\Article;
use Antarian\Stantie\Model\Category;
use Antarian\Stantie\Model\Series;
use DateTimeImmutable;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CategoryFactory
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function create(
        string $slug,
        string $title,
    ): Category {
        $filePath = 'category-' . $slug . '.html';

        $category = new Category(
            slug: $slug,
            title: $title,
            filePath: $filePath,
        );

        $violations = $this->validator->validate($category);

        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                $violationMessages[] = $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }

        return $category;
    }
}