<?php

class EntityConfigLoader
{
    private $configConverter;
    private $configNormalizer;

    public function __construct(
        ConfigConverter$configConverter,
        ConfigNormalizer $configNormalizer
    ) {
        $this->configConverter = $configConverter;
        $this->configNormalizer = $configNormalizer;
    }

    public function load($entityConfigName)
    {
        $config = [];

        return $this->normalizeConfig($config);
    }

     /**
     * @param EntityConfig|array $config
     *
     * @return EntityConfig
     */
    protected function normalizeConfig($config)
    {
        if ($config instanceof EntityConfig) {
            $config = $config->toArray();
        }

        return $this->configConverter->convertConfig(
            $this->configNormalizer->normalizeConfig($config)
        );
    }
}
