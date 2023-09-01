<?php

namespace EnjoysCMS\Module\Sitemap\Composer\Scripts;

use Enjoys\Config\Config;
use EnjoysCMS\Core\Console\Utils\CommandsManage;
use EnjoysCMS\Module\Catalog\Command\CurrencyRate;
use EnjoysCMS\Module\Sitemap\Command\Generate;
use EnjoysCMS\Module\Sitemap\Command\Status;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleCommandsRegisterCommand extends Command
{

    private array $commands = [
        Generate::class => [],
        Status::class => [],
    ];

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->commands === []){
            return Command::SUCCESS;
        }
        $container = include __DIR__ . '/../../../bootstrap.php';
        $commandManage = new CommandsManage(config: $container->get(Config::class));
        $registeredCommands = $commandManage->registerCommands($this->commands);
        $changed = false;
        $output->writeln('<info>Register console commands [Sitemap]</info>');
        foreach ($registeredCommands as $command) {
            $output->writeln(sprintf(' <options=bold>- %s</></info>', $command));
            $changed = true;
        }
        if ($changed) {
            $commandManage->save();
        }
        return Command::SUCCESS;
    }
}
