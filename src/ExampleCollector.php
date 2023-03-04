<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap;


use samdark\sitemap\Sitemap;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExampleCollector implements SitemapCollectorInterface
{
    protected array $routeNames;


    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
        $this->routeNames = [
            'system/index' => [
                'frequency' => Sitemap::DAILY,
                'modified' => new \DateTimeImmutable()
            ],
            'system/login' => [
                'parameters' => [
                    'parameter_one' => 1,
                    'parameter_two' => 2
                ]
            ]
        ];
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->urlGenerator->getContext()->setBaseUrl($baseUrl);
    }

    public function make(): \Generator
    {

        foreach ($this->routeNames as $routeName => $params) {
              yield new Url(
                loc: $this->urlGenerator->generate($routeName, $params['parameters'] ?? [], UrlGeneratorInterface::ABSOLUTE_URL),
                modified: $params['modified'] ?? null,
                frequency: $params['frequency'] ?? null,
                priority: $params['priority'] ?? null
            );
        }
    }
}
