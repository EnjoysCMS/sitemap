<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap;


use EnjoysCMS\Core\Components\Modules\ModuleConfig;
use Psr\Container\ContainerInterface;

final class Configuration
{
    private ModuleConfig $moduleConfig;

    public function __construct(ContainerInterface $container)
    {
        $this->moduleConfig = new ModuleConfig('enjoyscms/sitemap', $container);
    }

    public function getModuleConfig(): ModuleConfig
    {
        return $this->moduleConfig;
    }
}
