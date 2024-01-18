<?php
declare(strict_types=1);
namespace Antarian\Stantie\Model\Factory;

use Antarian\Stantie\Exception\ValidationException;
use Antarian\Stantie\Model\Metadata;
use DateTimeImmutable;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MetadataFactory
{
    const DATE_FORMAT = 'jS M Y';

    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function createFromArray(
        array $data,
    ): Metadata {
        $published = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $data['published']);
        if (!$published || $published->format(self::DATE_FORMAT) !== $data['published']) {
            throw new ValidationException(sprintf('Please provide correct date in a correct format \'%s\' date provided \'%s\'', self::DATE_FORMAT, $data['published']));
        }

        $metadata = new Metadata(
            title: $data['title'],
            preview: $data['preview'],
            slug: $data['slug'],
            categorySlug: $data['categorySlug'],
            seriesSlug: $data['seriesSlug'],
            seriesPart: $data['seriesPart'],
            author: $data['author'],
            published: $published,
            archived: $data['archived'],
        );

        $violations = $this->validator->validate($metadata);

        if (0 !== count($violations)) {
            $violationMessages = [];
            foreach ($violations as $violation) {
                $violationMessages[] = $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $violationMessages));
        }

        return $metadata;
    }
}