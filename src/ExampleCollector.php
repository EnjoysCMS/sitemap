<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap;

use DateTimeImmutable;
use Generator;
use samdark\sitemap\Sitemap;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExampleCollector implements SitemapCollectorInterface
{
    protected array $routeNames;


    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
        $this->routeNames = [
            'system/index' => [
                'frequency' => Sitemap::DAILY,
                'modified' => new DateTimeImmutable()
            ],
            'system/login' => [
                'parameters' => [
                    'parameter_one' => 1,
                    'parameter_two' => 2
                ]
            ]
        ];
    }

    public function make(): Generator
    {
        foreach ($this->routeNames as $routeName => $params) {
            yield new Url(
                loc: $this->urlGenerator->generate(
                    $routeName,
                    $params['parameters'] ?? [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                modified: $params['modified'] ?? null,
                frequency: $params['frequency'] ?? null,
                priority: $params['priority'] ?? null
            );
        }
    }
}
