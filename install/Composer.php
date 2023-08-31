<?php

namespace EnjoysCMS\Module\Sitemap\Install;

use App\Install\Functions\CommandsManage;
use Composer\Script\Event;
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
    public static function registerCommands(Event $event): void
    {
        $manage = new CommandsManage(
            dirname($event->getComposer()->getConfig()->getConfigSource()->getName()) . '/console.yml'
        );
        $manage->registerCommands(self::$commands);
        $manage->save();
    }
}
