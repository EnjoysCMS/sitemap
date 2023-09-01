<?php

namespace EnjoysCMS\Module\Sitemap\Composer\Scripts;

use EnjoysCMS\Core\Composer\Scripts\RegisterCommandsToConsoleCommand;
use EnjoysCMS\Module\Sitemap\Command\Generate;
use EnjoysCMS\Module\Sitemap\Command\Status;

class ConsoleCommandsRegisterCommand extends RegisterCommandsToConsoleCommand
{

    protected string $moduleName = 'Sitemap';
    protected array $commands = [
        Generate::class => [],
        Status::class => [],
    ];


}
