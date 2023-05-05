<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap;

use Generator;

interface SitemapCollectorInterface
{
    public function make(): Generator;
}
