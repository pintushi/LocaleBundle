<?php

namespace Pintushi\Bundle\NavigationBundle\Provider;

use Knp\Menu\ItemInterface;
use Pintushi\Bundle\NavigationBundle\Manager\MenuUpdateManager;
use Pintushi\Bundle\NavigationBundle\Menu\ConfigurationBuilder;

class MenuUpdateProvider implements MenuUpdateProviderInterface
{
    /**
     * @var MenuUpdateManager
     */
    private $menuUpdateManager;

    /**
     * @param MenuUpdateManager $menuUpdateManager
     */
    public function __construct(MenuUpdateManager $menuUpdateManager)
    {
        $this->menuUpdateManager = $menuUpdateManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getMenuUpdatesForMenuItem(ItemInterface $menuItem)
    {
        $repo = $this->menuUpdateManager->getRepository();

        return $repo->findMenuUpdates($menuItem->getName());
    }
}
