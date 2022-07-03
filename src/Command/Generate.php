<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Command;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'generate',
    description: 'Генерация sitemap.xml'
)]
final class Generate extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        dd($input->getOptions());
    }
}
