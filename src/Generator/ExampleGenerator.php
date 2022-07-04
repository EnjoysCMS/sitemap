<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Generator;


use EnjoysCMS\Module\Sitemap\SitemapGeneratorInterface;
use samdark\sitemap\Sitemap;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Enjoys\FileSystem\createDirectory;

final class ExampleGenerator implements SitemapGeneratorInterface
{
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator->getContext()->setHost($_ENV['SERVER_NAME']);
        createDirectory($_ENV['PUBLIC_DIR'] . '/sitemaps');
        $this->sitemap = new Sitemap($_ENV['PUBLIC_DIR'] . '/sitemaps/sitemap_example.xml');
    }

    public function make()
    {
        $this->sitemap->addItem($this->urlGenerator->generate('system/index', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->sitemap->addItem($this->urlGenerator->generate('system/login', [], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->sitemap->write();
        return $this->sitemap->getSitemapUrls(
            $this->urlGenerator->getContext()->getScheme() . '://' . $this->urlGenerator->getContext()->getHost() . '/sitemaps/'
        );
    }
}
