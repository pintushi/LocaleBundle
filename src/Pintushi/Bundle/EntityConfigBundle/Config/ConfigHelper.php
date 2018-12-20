<?php

namespace Pintushi\Bundle\EntityConfigBundle\Config;

use Pintushi\Bundle\EntityConfigBundle\Entity\EntityConfigModel;
use Pintushi\Bundle\EntityConfigBundle\Entity\FieldConfigModel;

class ConfigHelper
{
    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * @return array
     */
    public function getExtendRequireJsModules()
    {
        return $this->configManager
            ->getProvider('extend')
            ->getPropertyConfig()
            ->getRequireJsModules();
    }

    /**
     * @param FieldConfigModel $fieldConfigModel
     * @param string $scope
     * @return ConfigInterface
     */
    public function getEntityConfigByField(FieldConfigModel $fieldConfigModel, $scope)
    {
        $configProvider = $this->configManager->getProvider($scope);

        return $configProvider->getConfig($fieldConfigModel->getEntity()->getClassName());
    }

    /**
     * @param FieldConfigModel $fieldConfigModel
     * @param string $scope
     * @return ConfigInterface
     */
    public function getFieldConfig(FieldConfigModel $fieldConfigModel, $scope)
    {
        $configProvider = $this->configManager->getProvider($scope);

        return $configProvider->getConfig(
            $fieldConfigModel->getEntity()->getClassName(),
            $fieldConfigModel->getFieldName()
        );
    }

    /**
     * @param FieldConfigModel $field
     * @param $scope
     * @param callable $callback
     * @return ConfigInterface[]
     */
    public function filterEntityConfigByField(FieldConfigModel $field, $scope, callable $callback)
    {
        $configProvider = $this->configManager->getProvider($scope);

        return $configProvider->filter($callback, $field->getEntity()->getClassName());
    }

    /**
     * @param FieldConfigModel $fieldModel
     * @param array $options
     */
    public function updateFieldConfigs(FieldConfigModel $fieldModel, $options)
    {
        $className = $fieldModel->getEntity()->getClassName();
        $fieldName = $fieldModel->getFieldName();
        foreach ($options as $scope => $scopeValues) {
            $configProvider = $this->configManager->getProvider($scope);
            $config = $configProvider->getConfig($className, $fieldName);
            $hasChanges = false;
            foreach ($scopeValues as $code => $val) {
                if (!$config->is($code, $val)) {
                    $config->set($code, $val);
                    $hasChanges = true;
                }
            }
            if ($hasChanges) {
                $this->configManager->persist($config);
                $indexedValues = $configProvider->getPropertyConfig()->getIndexedValues($config->getId());
                $fieldModel->fromArray($config->getId()->getScope(), $config->all(), $indexedValues);
            }
        }
    }

    /**
     * @param EntityConfigModel $entityConfigModel
     * @param string $scope
     * @return ConfigInterface
     */
    public function getEntityConfig(EntityConfigModel $entityConfigModel, $scope)
    {
        $configProvider = $this->configManager->getProvider($scope);

        return $configProvider->getConfig($entityConfigModel->getClassName());
    }

    /**
     * @param ConfigInterface $extendEntityConfig
     * @param $fieldType
     * @param array $additionalFieldOptions
     * @return array
     */
    public function createFieldOptions(
        ConfigInterface $extendEntityConfig,
        $fieldType,
        array $additionalFieldOptions = []
    ) {
        $fieldOptions = [
            'extend' => [
                'is_extend' => true,
            ]
        ];

        $fieldOptions = array_merge_recursive($fieldOptions, $additionalFieldOptions);
        // check if a field type is complex, for example reverse relation or public enum
        $fieldTypeParts = explode('||', $fieldType);
        if (count($fieldTypeParts) > 1) {
            if (in_array($fieldTypeParts[0], ['enum', 'multiEnum'], true)) {
                // enum
                $fieldType = $fieldTypeParts[0];
                $fieldOptions['enum']['enum_code'] = $fieldTypeParts[1];
            }
        }

        return [$fieldType, $fieldOptions];
    }
}
