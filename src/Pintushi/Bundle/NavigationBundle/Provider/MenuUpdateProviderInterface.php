<?php

namespace Pintushi\Bundle\NavigationBundle\Provider;

use Knp\Menu\ItemInterface;
use Pintushi\Bundle\NavigationBundle\Entity\MenuUpdateInterface;

interface MenuUpdateProviderInterface
{
    /**
     * @param ItemInterface $menuItem
     * @param array         $options
     *
     * @return MenuUpdateInterface[]
     */
    public function getMenuUpdatesForMenuItem(ItemInterface $menuItem, array $options = []);
}
