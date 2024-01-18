<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap\Command;

use DI\DependencyException;
use DI\FactoryInterface;
use DI\NotFoundException;
use EnjoysCMS\Module\Sitemap\Config;
use EnjoysCMS\Module\Sitemap\SitemapCollectorInterface;
use EnjoysCMS\Module\Sitemap\Url;
use InvalidArgumentException;
use samdark\sitemap\Index;
use samdark\sitemap\Sitemap;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'sitemap:generate',
    description: 'Генерация sitemap.xml'
)]
final class Generate extends Command
{

    private string $baseUrl;

    public function __construct(
        private readonly FactoryInterface $factory,
        UrlGeneratorInterface $urlGenerator,
        private readonly Config $config
    ) {
        parent::__construct();
        $this->baseUrl = (string)$this->config->get('baseUrl');
        $urlGenerator->getContext()->setBaseUrl($this->baseUrl);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->validateConfiguration();

        $sitemap = new Sitemap($_ENV['PUBLIC_DIR'] . $this->config->get('filename'), true);
        $sitemap->setMaxUrls($this->config->get('maxUrls'));
        $sitemap->setUseGzip($this->config->get('useGzip', false));
        $sitemap->setMaxBytes($this->config->get('maxBytes', 10 * 1024 * 1024));
        $sitemap->setBufferSize($this->config->get('bufferSize', 10));
        $sitemap->setUseIndent($this->config->get('useIndent', false));

        if (null !== $stylesheet = $this->config->get('stylesheet')) {
            try {
                $sitemap->setStylesheet($stylesheet);
            } catch (\InvalidArgumentException) {
                $sitemap->setStylesheet($this->baseUrl . $stylesheet);
            }
        }

        foreach ($this->config->get('collectors') as $class) {
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
            $generator = $this->factory->make($class, $params);

            /** @var Url $url */
            foreach ($generator->make() as $url) {
                $sitemap->addItem(
                    $url->getLoc(),
                    $url->getModified(),
                    $url->getFrequency(),
                    $url->getPriority()
                );
                $output->writeln(sprintf('%s - Added', $url->getLoc()), OutputInterface::VERBOSITY_DEBUG);
            }

            $output->writeln(sprintf('%s - OK', $generator::class), OutputInterface::VERBOSITY_VERBOSE);
        }

        $sitemap->write();

        $sitemaps = $sitemap->getSitemapUrls(rtrim($this->config->get('baseUrl'), '/') . '/');

        foreach (
            array_diff(
                glob($_ENV['PUBLIC_DIR'] . str_replace('.xml', '*.xml', $this->config->get('filename'))),
                $sitemap->getWrittenFilePath()
            ) as $item
        ) {
            @unlink($item);
        }


        if (count($sitemaps) > 1) {
            $index = new Index(
                $_ENV['PUBLIC_DIR'] . str_replace('.xml', '_index.xml', $this->config->get('filename'))
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

    private function validateConfiguration(): void
    {
        if (!preg_match('/^https?:\/\//', $this->baseUrl)) {
            throw new InvalidArgumentException(
                sprintf(
                    'baseUrl is not correct [%s] Need url with scheme: http:// or https://',
                    $this->config->get('baseUrl')
                )
            );
        }
    }
}
