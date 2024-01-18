<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model\Factory;

use Antarian\Stantie\Exception\ValidationException;
use Antarian\Stantie\Model\Series;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SeriesFactory
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function create(
        string $slug,
        string $title,
        string $pretext,
        int $parts,
    ): Series {
        $filePath = 'series-' . $slug . '.html';

        $series = new Series(
            slug: $slug,
            title: $title,
            pretext: $pretext,
            parts: $parts,
            filePath: $filePath,
        );

        $violations = $this->validator->validate($series);

        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                $violationMessages[] = $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }

        return $series;
    }
}