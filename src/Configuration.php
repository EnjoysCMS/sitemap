<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap;


use DI\DependencyException;
use DI\FactoryInterface;
use DI\NotFoundException;
use EnjoysCMS\Core\Components\Modules\ModuleConfig;

final class Configuration
{
    private ModuleConfig $config;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->config = $factory->make(ModuleConfig::class, ['moduleName' => 'enjoyscms/sitemap']);
    }

    public function getModuleConfig(): ModuleConfig
    {
        return $this->config;
    }
}
