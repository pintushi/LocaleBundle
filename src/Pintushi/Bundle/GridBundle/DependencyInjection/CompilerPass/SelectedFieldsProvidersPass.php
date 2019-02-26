<?php

namespace Pintushi\Bundle\GridBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Collects selected fields providers by tag and adds them to composite provider service.
 */
class SelectedFieldsProvidersPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    private const COMPOSITE_PROVIDER_ID = 'pintushi_grid.provider.selected_fields';
    private const TAG_NAME = 'pintushi_grid.selected_fields_provider';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(self::COMPOSITE_PROVIDER_ID)) {
            return;
        }

        $sortedServices = $this->findAndSortTaggedServices(self::TAG_NAME, $container);

        $service = $container->getDefinition(self::COMPOSITE_PROVIDER_ID);
        foreach ($sortedServices as $taggedService) {
            $service->addMethodCall('addSelectedFieldsProvider', [$taggedService]);
        }
    }
}
