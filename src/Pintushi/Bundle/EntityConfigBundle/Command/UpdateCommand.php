<?php

namespace Pintushi\Bundle\EntityConfigBundle\Command;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Pintushi\Component\Log\OutputLogger;

use Pintushi\Bundle\EntityConfigBundle\Tools\ConfigLogger;
use Pintushi\Bundle\EntityConfigBundle\Tools\ConfigLoader;

class UpdateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('pintushi:entity-config:update')
            ->setDescription('Updates configuration data for entities.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force overwrite config\'s option values')
            ->addOption(
                'filter',
                null,
                InputOption::VALUE_OPTIONAL,
                'Entity class name filter(regExp)'
                . ', for example: \'App\\\\Bundle\\\\User*\', \'^App\\\\(.*)\\\\Region$\''
            )
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Outputs modifications without apply them'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Update configuration data for entities');

        $filter = $input->getOption('filter');
        if ($filter) {
            $filter = function ($doctrineAllMetadata) use ($filter) {
                return array_filter(
                    $doctrineAllMetadata,
                    function ($item) use ($filter) {
                        /** @var ClassMetadataInfo $item */
                        return preg_match('/' . str_replace('\\', '\\\\', $filter) . '/', $item->getName());
                    }
                );
            };
        }

        $verbosity = $output->getVerbosity();
        if (!$input->getOption('dry-run')) {
            $verbosity--;
        }
        $logger = new ConfigLogger(new OutputLogger($output, true, $verbosity));
        /** @var ConfigLoader $loader */
        $loader = $this->getContainer()->get('pintushi_entity_config.config_loader');
        $loader->load(
            $input->getOption('force'),
            $filter,
            $logger,
            $input->getOption('dry-run')
        );
    }
}
