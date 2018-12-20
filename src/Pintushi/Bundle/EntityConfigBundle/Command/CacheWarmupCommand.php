<?php

namespace Pintushi\Bundle\EntityConfigBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Pintushi\Bundle\EntityConfigBundle\Config\ConfigCacheWarmer;

class CacheWarmupCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('pintushi:entity-config:cache:warmup')
            ->setDescription('Warms up the entity config cache.');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Warm up the entity config cache');

        /** @var ConfigCacheWarmer $configCacheWarmer */
        $configCacheWarmer = $this->getContainer()->get('pintushi_entity_config.config_cache_warmer');
        $configCacheWarmer->warmUpCache();
    }
}
