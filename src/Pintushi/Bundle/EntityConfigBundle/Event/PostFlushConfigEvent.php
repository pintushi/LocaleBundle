<?php

namespace Pintushi\Bundle\EntityConfigBundle\Event;

use Pintushi\Bundle\EntityConfigBundle\Entity\ConfigModel;
use Pintushi\Bundle\EntityConfigBundle\Config\ConfigManager;

class PostFlushConfigEvent extends Event
{
    /** @var ConfigModel[] */
    protected $models;

    /**
     * @param ConfigModel[] $models        Flushed entity and field config models
     * @param ConfigManager $configManager The entity config manager
     */
    public function __construct(array $models, ConfigManager $configManager)
    {
        $this->models        = $models;
        $this->configManager = $configManager;
    }

    /**
     * @return ConfigModel[]
     */
    public function getModels()
    {
        return $this->models;
    }
}
