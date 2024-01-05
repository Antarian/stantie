<?php
declare(strict_types=1);
namespace Antarian\Stantie\FileSystem;

use InvalidArgumentException;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Output\RenderedContentInterface;
use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class FileExtractor
{
    public function __construct(
        private MarkdownConverter $converter,
    ) {
    }

    /**
     * @param SplFileInfo $file
     * @return array
     */
    public function getYamlFileContent(SplFileInfo $file): array
    {
        $yamlContent = Yaml::parse($file->getContents());
        if (!is_array($yamlContent)) {
            throw new InvalidArgumentException(sprintf('Incorrect content in \'%s\' yaml file', $file->getFilename()));
        }

        return $yamlContent;
    }

    public function getMdFileContent(SplFileInfo $file): array
    {
        $renderedContent = $this->converter->convert($file->getContents());
        $htmlContent = $renderedContent->getContent();
        $metaData = $this->extractMetadata($renderedContent);

        return [
            'metadata' => $metaData,
            'htmlContent' => $htmlContent,
        ];
    }

    /**
     * @throws RuntimeException
     */
    private function extractMetadata(RenderedContentInterface $renderedContent): array
    {
        if (!$renderedContent instanceof RenderedContentWithFrontMatter) {
            throw new RuntimeException('Provided MarkDown files need to contain YAML front matter');
        }

        return $renderedContent->getFrontMatter();
    }
}