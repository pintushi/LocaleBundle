<?php

namespace Pintushi\Bundle\EntityConfigBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Pintushi\Bundle\EntityConfigBundle\Config\ConfigCacheWarmer;
use Pintushi\Bundle\EntityConfigBundle\Config\ConfigManager;

class CacheClearCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('pintushi:entity-config:cache:clear')
            ->setDescription('Clears the entity config cache.')
            ->addOption('no-warmup', null, InputOption::VALUE_NONE, 'Do not warm up the cache.');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Clear the entity config cache');

        /** @var ConfigManager $configManager */
        $configManager = $this->getContainer()->get('pintushi_entity_config.config_manager');
        $configManager->flushAllCaches();

        if (!$input->getOption('no-warmup')) {
            /** @var ConfigCacheWarmer $configCacheWarmer */
            $configCacheWarmer = $this->getContainer()->get('pintushi_entity_config.config_cache_warmer');
            $configCacheWarmer->warmUpCache();
        }
    }
}
