<?php

namespace Pintushi\Bundle\EntityConfigBundle\CacheWarmer;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;

use Pintushi\Bundle\EntityConfigBundle\Config\ConfigManager;
use Pintushi\Bundle\EntityConfigBundle\Config\Id\ConfigIdInterface;

class ClassMigration
{
    /** @var ManagerRegistry */
    private $managerRegistry;

    /** @var ConfigManager */
    private $configManager;

    /** @var bool */
    private $applicationInstalled;

    /** @var string[] */
    protected $config = [
        'OroB2B\Bundle\AccountBundle' => 'OroB2B\Bundle\CustomerBundle',
    ];

    /**
     * @param ManagerRegistry $managerRegistry
     * @param ConfigManager $configManager
     * @param bool $applicationInstalled
     */
    public function __construct(ManagerRegistry $managerRegistry, ConfigManager $configManager, $applicationInstalled)
    {
        $this->managerRegistry = $managerRegistry;
        $this->configManager = $configManager;
        $this->applicationInstalled = (bool)$applicationInstalled;
    }

    /**
     * @param bool $skipp
     */
    public function migrate($skipp = true)
    {
        if ($skipp) {
            return;
        }

        $config = $this->config;
        if (!$config) {
            throw new \InvalidArgumentException('Migration not configured');
        }

        if (!$this->applicationInstalled) {
            return;
        }

        foreach ($config as $from => $to) {
            $this->migrateTables($from, $to);
        }
    }

    /**
     * @param string $from
     * @param string $to
     */
    protected function migrateTables($from, $to)
    {
        /** @var Connection $defaultConnection */
        $defaultConnection = $this->managerRegistry->getConnection();

        /** @var Connection $configConnection */
        $configConnection = $this->managerRegistry->getConnection('config');
        /** @var Connection $searchConnection */
        $searchConnection = $this->managerRegistry->getConnection('search');

        $defaultConnection->beginTransaction();
        $configConnection->beginTransaction();
        $searchConnection->beginTransaction();
        try {
            $this->migrateTableColumn($defaultConnection, 'pintushi_migrations', 'bundle', $from, $to);
            $this->migrateTableColumn($defaultConnection, 'pintushi_migrations_data', 'class_name', $from, $to);

            $this->migrateTableColumn($searchConnection, 'pintushi_search_item', 'entity', $from, $to);
            $this->migrateTableColumn($searchConnection, 'pintushi_search_item', 'alias', $from, $to);
            $this->migrateTableColumn($searchConnection, 'pintushi_search_index_integer', 'field', $from, $to);

            $this->updateEntityConfigTable($configConnection, $from, $to);
            $this->updateEntityConfigFieldTables($configConnection, $from, $to);

            $defaultConnection->commit();
            $configConnection->commit();
            $searchConnection->commit();
        } catch (\Exception $e) {
            $defaultConnection->rollBack();
            $configConnection->rollBack();
            $searchConnection->rollBack();
            throw $e;
        }

        $this->configManager->clear();
    }

    /**
     * @param Connection $configConnection
     * @param string $from
     * @param string $to
     */
    protected function updateEntityConfigTable(Connection $configConnection, $from, $to)
    {
        $entities = $configConnection->fetchAll('SELECT id, class_name, data FROM pintushi_entity_config');
        foreach ($entities as $entity) {
            $id = $entity['id'];
            $originalClassName = $entity['class_name'];
            $originalData = $entity['data'];
            $originalData = $originalData ? $configConnection->convertToPHPValue($originalData, Type::TARRAY) : [];

            $className = $this->replaceStringValue($originalClassName, $from, $to);
            $data = $this->replaceArrayValue($originalData, $from, $to);

            if ($className !== $originalClassName || $data !== $originalData) {
                $data = $configConnection->convertToDatabaseValue($data, Type::TARRAY);

                $sql = 'UPDATE pintushi_entity_config SET class_name = ?, data = ? WHERE id = ?';
                $parameters = [$className, $data, $id];
                $configConnection->executeUpdate($sql, $parameters);
            }
        }
    }

    /**
     * @param Connection $configConnection
     * @param string $from
     * @param string $to
     */
    protected function updateEntityConfigFieldTables(Connection $configConnection, $from, $to)
    {
        $fields = $configConnection->fetchAll('SELECT id, data FROM pintushi_entity_config_field');
        foreach ($fields as $field) {
            $id = $field['id'];
            $originalData = $field['data'];
            $originalData = $originalData ? $configConnection->convertToPHPValue($originalData, Type::TARRAY) : [];

            $data = $this->replaceArrayValue($originalData, $from, $to);

            if ($data !== $originalData) {
                $data = $configConnection->convertToDatabaseValue($data, Type::TARRAY);

                $sql = 'UPDATE pintushi_entity_config_field SET data = ? WHERE id = ?';
                $parameters = [$data, $id];
                $configConnection->executeUpdate($sql, $parameters);
            }
        }

        $indexValues = $configConnection->fetchAll(
            "SELECT id, value FROM pintushi_entity_config_index_value WHERE code = 'module_name'"
        );
        foreach ($indexValues as $indexValue) {
            $id = $indexValue['id'];
            $originalValue = $indexValue['value'];

            $value = $this->replaceStringValue($originalValue, $from, $to);

            if ($value !== $originalValue) {
                $sql = 'UPDATE pintushi_entity_config_index_value SET value = ? WHERE id = ?';
                $parameters = [$value, $id];
                $configConnection->executeUpdate($sql, $parameters);
            }
        }
    }

    /**
     * @param Connection $connection
     * @param string $from
     * @param string $to
     * @param string $table
     * @param string $column
     */
    protected function migrateTableColumn(Connection $connection, $table, $column, $from, $to)
    {
        $preparedFrom = str_replace('\\', '\\\\', $from);
        $rows = $connection->fetchAll("SELECT id, $column FROM $table WHERE $column LIKE '%$preparedFrom%'");
        foreach ($rows as $row) {
            $id = $row['id'];
            $originalValue = $row[$column];
            $alteredValue = $this->replaceStringValue($originalValue, $from, $to);
            if ($alteredValue !== $originalValue) {
                $connection->executeQuery("UPDATE $table SET $column = ? WHERE id = ?", [$alteredValue, $id]);
            }
        }
    }

    /**
     * @param array $data
     * @return array
     */
    protected function replaceArrayValue(array $data, $from, $to)
    {
        foreach ($data as $originalKey => $value) {
            $key = $this->replaceStringValue($originalKey, $from, $to);
            if ($key !== $originalKey) {
                unset($data[$originalKey]);
                $data[$key] = $value;
            }
            if (is_array($value)) {
                $data[$key] = $this->replaceArrayValue($value, $from, $to);
            } elseif (is_string($value)) {
                $data[$key] = $this->replaceStringValue($value, $from, $to);
            } elseif ($value instanceof ConfigIdInterface) {
                $originalClass = $value->getClassName();
                $alteredClass = $this->replaceStringValue($originalClass, $from, $to);
                if ($alteredClass !== $originalClass) {
                    $reflectionProperty = new \ReflectionProperty(get_class($value), 'className');
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($value, $alteredClass);
                }
            }
        }

        return $data;
    }

    /**
     * @param string $value
     * @param string $from
     * @param string $to
     * @return string
     */
    protected function replaceStringValue($value, $from, $to)
    {
        if (!is_string($value)) {
            return $value;
        }

        return str_replace([$from], [$to], $value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function replaceStringValues($value)
    {
        if (!$this->config) {
            throw new \InvalidArgumentException('Migration not configured');
        }

        if (!is_string($value)) {
            return $value;
        }

        return str_replace(array_keys($this->config), array_values($this->config), $value);
    }

//    /**
//     * @param string $from
//     * @param string $to
//     * @param int $priority
//     */
//    public function append($from, $to, $priority = 1)
//    {
//        $this->config[(string)$from] = (string)$to;
//
//        return $this;
//    }
}
