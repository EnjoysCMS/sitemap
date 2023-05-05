<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap;


interface SitemapCollectorInterface
{

    public function make();

    /**
     * @deprecated remove in 2.0
     */
    public function setBaseUrl(string $baseUrl);
}
