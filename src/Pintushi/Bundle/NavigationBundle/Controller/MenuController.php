<?php

namespace Pintushi\Bundle\NavigationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pintushi\Bundle\NavigationBundle\Provider\BuilderChainProvider;
use Pintushi\Bundle\NavigationBundle\Provider\MenuUpdateProvider;
use Pintushi\Bundle\NavigationBundle\Config\MenuConfiguration;

abstract class MenuController extends Controller
{
    private $menuConfiguration;

    private $builderChainProvider;

    public function __construct(MenuConfiguration $menuConfiguration, BuilderChainProvider $builderChainProvider)
    {
        $this->menuConfiguration = $menuConfiguration;
        $this->builderChainProvider = $builderChainProvider;
    }

     /**
     * @param string $menuName
     * @return ItemInterface
     */
    protected function getMenu($menuName)
    {
        $options = [
            BuilderChainProvider::IGNORE_CACHE_OPTION => true,
            BuilderChainProvider::MENU_LOCAL_CACHE_PREFIX => 'edit_'
        ];

        $configurationRootMenuKeys = array_keys($this->menuConfiguration->getTree());
        $isMenuFromConfiguration = in_array($menuName, $configurationRootMenuKeys, true);

        $menu = $this->builderChainProvider->get($menuName, $options);

        if (!$isMenuFromConfiguration && !count($menu->getChildren())) {
            throw $this->createNotFoundException(sprintf("Menu \"%s\" not found.", $menuName));
        }

        return $menu;
    }
}