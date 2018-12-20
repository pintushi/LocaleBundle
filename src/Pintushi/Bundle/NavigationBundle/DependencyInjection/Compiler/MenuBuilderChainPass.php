<?php
namespace Pintushi\Bundle\NavigationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Pintushi\Bundle\NavigationBundle\Provider\BuilderChainProvider;

class MenuBuilderChainPass implements CompilerPassInterface
{
    const MENU_BUILDER_TAG = 'pintushi_menu.builder';
    const MENU_HELPER_SERVICE = 'knp_menu.helper';

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition(self::MENU_HELPER_SERVICE)->setPublic(true);

        $this->processMenu($container);
    }

    protected function processMenu(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(BuilderChainProvider::class)) {
            return;
        }

        $definition = $container->getDefinition(BuilderChainProvider::class);
        $taggedServices = $container->findTaggedServiceIds(self::MENU_BUILDER_TAG);

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $addBuilderArgs = array(new Reference($id));

                if (!empty($attributes['alias'])) {
                    $addBuilderArgs[] = $attributes['alias'];
                }

                $definition->addMethodCall('addBuilder', $addBuilderArgs);
            }
        }
    }
}
