<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Command;


use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Enjoys\Config\Config;
use EnjoysCMS\Module\Sitemap\SitemapGeneratorInterface;
use samdark\sitemap\Index;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'generate',
    description: 'Генерация sitemap.xml'
)]
final class Generate extends Command
{
    private Config $config;


    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public function __construct(private Container $container)
    {
        parent::__construct();
        $this->config = $container->get(Config::class);
        $this->config->addConfig(
            $_ENV['PROJECT_DIR'] . '/sitemap.yml',
            ['flags' => Yaml::PARSE_CONSTANT],
            Config::YAML
        );
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $index = new Index($_ENV['PUBLIC_DIR'].'/sitemap.xml');

        foreach ($this->config->getConfig() as $class => $params) {


            if (!class_exists($class) || !class_implements($class)){
                continue;
            }
            /** @var SitemapGeneratorInterface $generator */
            $generator = $this->container->make($class, (array)$params);
            $urls = $generator->make();
            foreach ($urls as $url) {
                $index->addSitemap($url);
            }
            $index->write();
            $output->writeln(sprintf('%s - OK', $generator::class));
        }
        return Command::SUCCESS;
    }
}
