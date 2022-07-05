<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Collector;


use EnjoysCMS\Module\Sitemap\SitemapCollectorInterface;
use EnjoysCMS\Module\Sitemap\Url;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ExampleCollector implements SitemapCollectorInterface
{
    private array $routeNames;


    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->routeNames = [
            'system/index',
            'system/login'
        ];
    }

    public function setBaseUrl(string $baseUrl)
    {
        $this->urlGenerator->getContext()->setBaseUrl($baseUrl);
    }

    public function make(): \Generator
    {

        foreach ($this->routeNames as $routeName => $params) {

            if (!is_array($params)){
                $routeName = $params;
                $params = [];
            }

            yield new Url(
                $this->urlGenerator->generate($routeName, $params, UrlGeneratorInterface::ABSOLUTE_URL),
                new \DateTimeImmutable()
            );
        }
    }
}
