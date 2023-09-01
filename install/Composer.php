<?php

namespace EnjoysCMS\Module\Sitemap\Install;

use Enjoys\Config\Config;
use EnjoysCMS\Core\Console\Utils\CommandsManage;
use EnjoysCMS\Module\Sitemap\Command\Generate;
use EnjoysCMS\Module\Sitemap\Command\Status;
use Exception;

class Composer
{

    private static array $commands = [
        Generate::class => [],
        Status::class => [],
    ];

    /**
     * @throws Exception
     */
    public static function registerCommands(): void
    {
        $container = include __DIR__ . '/../../../bootstrap.php';
        $manage = new CommandsManage(config: $container->get(Config::class));
        $manage->registerCommands(self::$commands);
        $manage->save();
    }
}
