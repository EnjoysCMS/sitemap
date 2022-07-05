<?php

declare(strict_types=1);


namespace EnjoysCMS\Module\Sitemap\Command;


use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use EnjoysCMS\Core\Components\Modules\ModuleConfig;
use EnjoysCMS\Module\Sitemap\Config;
use EnjoysCMS\Module\Sitemap\Configuration;
use EnjoysCMS\Module\Sitemap\SitemapCollectorInterface;
use EnjoysCMS\Module\Sitemap\Url;
use samdark\sitemap\Index;
use samdark\sitemap\Sitemap;
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
    private ModuleConfig $configuration;


    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws \Exception
     */
    public function __construct(private Container $container, Configuration $configuration)
    {
        parent::__construct();
        $this->configuration = $configuration->getModuleConfig();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->validateConfiguration();

        $sitemap = new Sitemap($_ENV['PUBLIC_DIR'] . $this->configuration->get('filename'), true);
        $sitemap->setMaxUrls($this->configuration->get('maxUrls'));
        $sitemap->setUseGzip($this->configuration->get('useGzip') ?? false);
        $sitemap->setMaxBytes($this->configuration->get('maxBytes') ?? 10*1024*1024);
        $sitemap->setBufferSize($this->configuration->get('bufferSize') ?? 10);
        $sitemap->setUseIndent($this->configuration->get('useIndent') ?? false);

        if ($this->configuration->get('stylesheet') !== null){
            $sitemap->setStylesheet($this->configuration->get('stylesheet'));
        }


       // dd($sitemap);
        foreach ($this->configuration->get('collectors') as $class) {
            $params = [];

            if (is_array($class)) {
                $params = (array)current($class);
                $class = key($class);
            }

            if (!class_exists($class)) {
                $output->writeln(sprintf('%s - Skip', $class), OutputInterface::VERBOSITY_VERBOSE);
                continue;
            }
            /** @var SitemapCollectorInterface $generator */
            $generator = $this->container->make($class, $params);
            $generator->setBaseUrl($this->configuration->get('baseUrl'));
            /** @var Url $url */
            foreach ($generator->make() as $url) {
                $sitemap->addItem(
                    $url->getLoc(),
                    $url->getModified()?->getTimestamp(),
                    $url->getFrequency(),
                    $url->getPriority()
                );
                $output->writeln(sprintf('%s - Added', $url->getLoc()), OutputInterface::VERBOSITY_DEBUG);
            }

            $output->writeln(sprintf('%s - OK', $generator::class), OutputInterface::VERBOSITY_VERBOSE);
        }

        $sitemap->write();

        $sitemaps = $sitemap->getSitemapUrls(rtrim($this->configuration->get('baseUrl'), '/') . '/');

        foreach (
            array_diff(
                glob($_ENV['PUBLIC_DIR'] . str_replace('.xml', '*.xml', $this->configuration->get('filename'))),
                $sitemap->getWrittenFilePath()
            ) as $item
        ) {
            @unlink($item);
        }


        if (count($sitemaps) > 1) {
            $index = new Index(
                $_ENV['PUBLIC_DIR'] . str_replace('.xml', '_index.xml', $this->configuration->get('filename'))
            );
            $output->writeln('Writing sitemap index', OutputInterface::VERBOSITY_VERBOSE);
            foreach ($sitemaps as $sitemapUrl) {
                $index->addSitemap($sitemapUrl);
                $output->writeln(sprintf('%s - Added', $sitemapUrl), OutputInterface::VERBOSITY_DEBUG);
            }
            $index->write();
        }


        return Command::SUCCESS;
    }

    private function validateConfiguration()
    {
        if (!in_array(
            strpos((string)$this->configuration->get('baseUrl'), '://'),
            [4, 5],
            true
        )) {
            throw new \InvalidArgumentException(
                sprintf(
                    'baseUrl is not correct [%s] Need url with scheme: http or https',
                    $this->configuration->get('baseUrl')
                )
            );
        }
    }
}
