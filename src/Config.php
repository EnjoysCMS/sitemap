<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap;


use EnjoysCMS\Core\Modules\AbstractModuleConfig;

final class Config extends AbstractModuleConfig
{
    public function getModulePackageName(): string
    {
        return 'enjoyscms/sitemap';
    }
}
