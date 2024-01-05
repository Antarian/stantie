<?php

use Antarian\Stantie\Builder\ContentBuilder;
use Antarian\Stantie\Builder\WebpageBuilder;
use Antarian\Stantie\FileSystem\FileExtractor;
use Antarian\Stantie\FileSystem\Finder;
use Antarian\Stantie\Filter\Filter;
use Antarian\Stantie\Sorter\Sorter;
use DI\Container;
use League\CommonMark\Environment\Environment as CommonMarkEnvironment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\MarkdownConverter;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/* just change $templatePath to 'templates' and start your own project based on the example */
$templatePath = __DIR__ . '/../templates';
$dataPath = __DIR__ . '/../data/';
$targetPath = __DIR__ . '/../build/';

// DI container
$container = new Container([
    LoaderInterface::class => function () use ($templatePath) {
        return new FilesystemLoader($templatePath);
    },
    MarkdownConverter::class => function () {
        $environment = new CommonMarkEnvironment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());
        return new MarkdownConverter($environment);
    },
    Finder::class => function () use ($dataPath) {
        return new Finder(dataPath: $dataPath);
    },
    ContentBuilder::class => function (ContainerInterface $c) {
        return new ContentBuilder(
            finder: $c->get(Finder::class),
            fileExtractor: $c->get(FileExtractor::class),
            filter: $c->get(Filter::class),
            sorter: $c->get(Sorter::class),
        );
    },
    WebpageBuilder::class => function (ContainerInterface $c) use ($targetPath) {
        return new WebpageBuilder(
            twig: $c->get(Environment::class),
            contentBuilder: $c->get(ContentBuilder::class),
            targetPath: $targetPath,
        );
    },
//    WebsiteBuilder::class => function (ContainerInterface $c) use ($targetPath) {
//        return new WebsiteBuilder(
//            twig: $c->get(Environment::class),
//            finder: $c->get(Finder::class),
//            subpagesBuilder: $c->get(SubpagesBuilder::class),
//            targetPath: $targetPath,
//        );
//    },
]);

$webpageBuilder = $container->get(WebpageBuilder::class);
$webpageBuilder();

//$websiteBuilder = $container->get(WebsiteBuilder::class);
//$websiteBuilder();
