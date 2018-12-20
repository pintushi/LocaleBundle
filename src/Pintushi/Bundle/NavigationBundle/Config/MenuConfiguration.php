<?php

namespace Pintushi\Bundle\NavigationBundle\Config;

use Pintushi\Bundle\NavigationBundle\Provider\ConfigurationProvider;

class MenuConfiguration
{
    /** @var ConfigurationProvider */
    private $configurationProvider;

    /**
     * @param ConfigurationProvider $configurationProvider
     */
    public function __construct(ConfigurationProvider $configurationProvider)
    {
        $this->configurationProvider = $configurationProvider;
    }

    /**
     * @return array
     */
    public function getTree()
    {
        $menuConfig = $this->configurationProvider->getConfiguration(ConfigurationProvider::MENU_CONFIG_KEY);
        if (!array_key_exists('tree', $menuConfig)) {
            return [];
        }

        return $menuConfig['tree'];
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $menuConfig = $this->configurationProvider->getConfiguration(ConfigurationProvider::MENU_CONFIG_KEY);
        if (!array_key_exists('items', $menuConfig)) {
            return [];
        }

        return $menuConfig['items'];
    }
}
