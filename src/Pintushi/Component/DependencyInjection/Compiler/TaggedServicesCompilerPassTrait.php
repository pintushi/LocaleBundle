<?php

namespace Pintushi\Component\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

trait TaggedServicesCompilerPassTrait
{
    public function registerTaggedServices(
        ContainerBuilder $container,
        string $serviceId,
        string $tagName,
        string $addMethodName
    ): void {
        if (!$container->hasDefinition($serviceId)) {
            return;
        }
        $taggedServiceIds = $container->findTaggedServiceIds($tagName);
        if (count($taggedServiceIds) === 0) {
            return;
        }

        $taggedServices = [];
        foreach ($taggedServiceIds as $id => $attributes) {
            $priority = 0;
            if (isset($attributes[0]['priority'])) {
                $priority = $attributes[0]['priority'];
            }
            $alias = $id;
            if (isset($attributes[0]['alias'])) {
                $alias = $attributes[0]['alias'];
            }
            $taggedServices[$priority][] = [new Reference($id), $alias];
        }

        // sort by priority ascending
        ksort($taggedServices);
        $sortedServices = [];
        foreach ($taggedServices as $services) {
            $sortedServices = array_merge($sortedServices, $services);
        }

        // register
        $service = $container->getDefinition($serviceId);
        foreach ($sortedServices as $taggedService) {
            $service->addMethodCall($addMethodName, $taggedService);
        }
    }
}
