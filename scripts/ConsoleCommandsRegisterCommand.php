<?php

namespace EnjoysCMS\Module\Sitemap\Composer\Scripts;

use EnjoysCMS\Core\Console\Command\AbstractCommandsRegister;
use EnjoysCMS\Module\Sitemap\Command\Generate;
use EnjoysCMS\Module\Sitemap\Command\Status;

class ConsoleCommandsRegisterCommand extends AbstractCommandsRegister
{

    protected string $moduleName = 'Sitemap';
    protected array $commands = [
        Generate::class => [],
        Status::class => [],
    ];


}
