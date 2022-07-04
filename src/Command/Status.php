<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Command;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'status',
    description: 'Статус sitemap.xml'
)]
final class Status extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('test');
//        if (true){
//            throw new \RuntimeException('test');
//        }
        return Command::SUCCESS;
    }
}
