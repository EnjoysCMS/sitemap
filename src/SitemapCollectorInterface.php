<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap;


interface SitemapCollectorInterface
{

    public function make();

    public function setBaseUrl(string $baseUrl);
}
