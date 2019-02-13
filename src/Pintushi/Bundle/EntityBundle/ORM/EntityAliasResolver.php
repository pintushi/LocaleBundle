<?php

namespace Pintushi\Bundle\EntityBundle\ORM;

use Doctrine\Common\Cache\Cache;
use Pintushi\Bundle\EntityBundle\Exception\EntityAliasNotFoundException;
use Pintushi\Bundle\EntityBundle\Exception\InvalidEntityAliasException;
use Pintushi\Bundle\EntityBundle\Model\EntityAlias;
use Pintushi\Bundle\EntityBundle\Provider\EntityAliasLoader;
use Pintushi\Bundle\EntityBundle\Provider\EntityAliasStorage;
use Psr\Log\LoggerInterface;

/**
 * Provides functionality to get singular and plural aliases for an entity class
 * and resolve entity class by any of these aliases.
 */
class EntityAliasResolver
{
    private const CACHE_KEY = 'entity_aliases';

    /** @var EntityAliasLoader */
    private $loader;

    /** @var Cache */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /** @var EntityAliasStorage|null */
    private $storage;

    /**
     * @param EntityAliasLoader $loader
     * @param Cache             $cache
     * @param LoggerInterface   $logger
     */
    public function __construct(EntityAliasLoader $loader, Cache $cache, LoggerInterface $logger)
    {
        $this->loader = $loader;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * Checks whether the given entity class has an alias.
     *
     * @param string $entityClass The FQCN of an entity
     *
     * @return bool
     */
    public function hasAlias($entityClass)
    {
        $this->ensureAllAliasesLoaded();

        return null !== $this->storage->getEntityAlias($entityClass);
    }

    /**
     * Returns the alias for the given entity class.
     *
     * @param string $entityClass The FQCN of an entity
     *
     * @return string The alias for the requested entity
     *
     * @throws EntityAliasNotFoundException if an alias not found
     */
    public function getAlias($entityClass)
    {
        $this->ensureAllAliasesLoaded();

        $entityAlias = $this->storage->getEntityAlias($entityClass);
        if (null === $entityAlias) {
            throw new EntityAliasNotFoundException(
                sprintf('An alias for "%s" entity not found.', $entityClass)
            );
        }

        return $entityAlias->getAlias();
    }

    /**
     * Returns the plural alias for the given entity class.
     *
     * @param string $entityClass The FQCN of an entity
     *
     * @return string The plural alias for the requested entity
     *
     * @throws EntityAliasNotFoundException if an alias not found
     */
    public function getPluralAlias($entityClass)
    {
        $this->ensureAllAliasesLoaded();

        $entityAlias = $this->storage->getEntityAlias($entityClass);
        if (null === $entityAlias) {
            throw new EntityAliasNotFoundException(
                sprintf('A plural alias for "%s" entity not found.', $entityClass)
            );
        }

        return $entityAlias->getPluralAlias();
    }

    /**
     * Returns the entity class by the given alias.
     *
     * @param string $alias The alias of an entity
     *
     * @return string The FQCN of an entity
     *
     * @throws EntityAliasNotFoundException if the given alias is not associated with any entity class
     */
    public function getClassByAlias($alias)
    {
        $this->ensureAllAliasesLoaded();

        $entityClass = $this->storage->getClassByAlias($alias);
        if (!$entityClass) {
            throw new EntityAliasNotFoundException(
                sprintf('The alias "%s" is not associated with any entity class.', $alias)
            );
        }

        return $entityClass;
    }

    /**
     * Returns the entity class by the given plural alias.
     *
     * @param string $pluralAlias The plural alias of an entity
     *
     * @return string The FQCN of an entity
     *
     * @throws EntityAliasNotFoundException if the given plural alias is not associated with any entity class
     */
    public function getClassByPluralAlias($pluralAlias)
    {
        $this->ensureAllAliasesLoaded();

        $entityClass = $this->storage->getClassByPluralAlias($pluralAlias);
        if (!$entityClass) {
            throw new EntityAliasNotFoundException(
                sprintf('The plural alias "%s" is not associated with any entity class.', $pluralAlias)
            );
        }

        return $entityClass;
    }

    /**
     * Returns all entity aliases.
     *
     * @return EntityAlias[]
     */
    public function getAll()
    {
        $this->ensureAllAliasesLoaded();

        return $this->storage->getAll();
    }

    /**
     * Warms up the cache.
     */
    public function warmUpCache()
    {
        $this->clearCache();
        $this->ensureAllAliasesLoaded();
    }

    /**
     * Clears the cache.
     */
    public function clearCache()
    {
        $this->cache->delete(self::CACHE_KEY);
        $this->storage = null;
    }

    /**
     * Creates a new instance of EntityAliasStorage.
     *
     * @return EntityAliasStorage
     */
    protected function createStorage()
    {
        return new EntityAliasStorage();
    }

    /**
     * Makes sure that aliases for all entities are loaded.
     */
    private function ensureAllAliasesLoaded()
    {
        if (null === $this->storage) {
            $cachedData = $this->cache->fetch(self::CACHE_KEY);
            if (false !== $cachedData) {
                $this->storage = $cachedData;
            } else {
                $storage = $this->createStorage();
                try {
                    $this->loader->load($storage);
                } catch (InvalidEntityAliasException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    $storage = null;
                    $this->logger->error('Loading of entity aliases failed', ['exception' => $e]);
                }
                if (null !== $storage) {
                    $this->cache->save(self::CACHE_KEY, $storage);
                    $this->storage = $storage;
                }
            }
        }
    }
}
