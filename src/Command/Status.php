<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap\Command;

use DateTimeImmutable;
use EnjoysCMS\Module\Sitemap\Config;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sitemap:status',
    description: 'Статус sitemap.xml'
)]
final class Status extends Command
{

    public function __construct(private readonly Config $config)
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $sitemaps = glob(
            $_ENV['PUBLIC_DIR'] . str_replace('.xml', '*.xml', $this->config->get('filename'))
        );

        if (empty($sitemaps)) {
            throw new Exception('Sitemaps not found');
        }

        $table = new Table($output);
        $table->setHeaders(['Filename', 'Created Date']);
        foreach (
            $sitemaps as $i => $item
        ) {
            $table->setRow($i, [
                str_replace($_ENV['PUBLIC_DIR'], '', $item),
                (new DateTimeImmutable('@' . stat($item)['mtime']))->format('d-m-Y H:i:s')
            ]);
        }
        $table->setStyle('borderless');
        $table->render();


        return Command::SUCCESS;
    }
}
