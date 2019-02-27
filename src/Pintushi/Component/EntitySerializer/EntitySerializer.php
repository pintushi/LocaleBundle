<?php

namespace Pintushi\Component\EntitySerializer;

use Doctrine\ORM\QueryBuilder;

/**
 * This configures a QueryBuilde based on a specified configuration.
 *
 * Example of configurations rules as following
 *  [
 *      // exclude the 'email' field
 *      'fields' => [
 *          // exclude the 'email' field
 *          'email'        => ['exclude' => true]
 *          // serialize the 'status' many-to-one relation using the value of the 'name' field
 *          'status'       => ['fields' => 'name'],
 *          // order the 'phones' many-to-many relation by the 'primary' field and
 *          // serialize each phone as a pair of 'phone' and 'primary' field
 *          'phones'       => [
 *              'exclusion_policy' => 'all',
 *              'fields'           => [
 *                  'phone'     => null,
 *                  'isPrimary' => null
 *              ],
 *              'order_by'         => [
 *                  'primary' => 'DESC'
 *              ]
 *          ],
 *          'addresses'    => [
 *              'fields'          => [
 *                  'owner'   => ['exclude' => true],
 *                  'country' => ['fields' => 'name'],
 *                  'types'   => [
 *                      'fields' => 'name',
 *                      'order_by' => [
 *                          'name' => 'ASC'
 *                      ]
 *                  ]
 *              ]
 *          ]
 *      ]
 *  ]
 *
 * The entity has the following fields:
 *  id
 *  name
 *  email
 *  status -> many-to-one
 *      name
 *      label
 *  phones -> many-to-many
 *      id
 *      phone
 *      primary
 *  addresses -> many-to-many
 *      id
 *      owner -> many-to-one
 *      country -> many-to-one
 *          code,
 *          name
 *      types -> many-to-many
 *          name
 *          label
 */
class EntitySerializer
{
    private $configConverter;
    private $doctrineHelper;
    private $fieldAccessor;
    private $configNormalizer;

    public function __construct(
        ConfigConverter$configConverter,
        DoctrineHelper $doctrineHelper,
        DoctrineHelper $fieldAccessor,
        ConfigNormalizer $configNormalizer
    ) {
        $this->configConverter = $configConverter;
        $this->configNormalizer = $configNormalizer;
        $this->doctrineHelper = $doctrineHelper;
        $this->fieldAccessor = $fieldAccessor;
    }

    public function configureQueryBuilder(QueryBuilder $queryBuilder, $config)
    {
        $entityConfig = $this->normalizeConfig($config);

        $this->updateQuery($queryBuilder, $entityConfig);

        return $queryBuilder;
    }


      /**
     * @param QueryBuilder $qb
     * @param EntityConfig $config
     */
    protected function updateQuery(QueryBuilder $qb, EntityConfig $config)
    {
        $rootAlias = $this->doctrineHelper->getRootAlias($qb);
        $entityClass = $this->doctrineHelper->getRootEntityClass($qb);
        $entityMetadata = $this->doctrineHelper->getEntityMetadata($entityClass);

        $qb->resetDQLPart('select');
        $this->updateSelectQueryPart($qb, $rootAlias, $entityClass, $config);

        $aliasCounter = 0;
        $fields = $this->fieldAccessor->getFields($entityClass, $config);
        foreach ($fields as $field) {
            $propertyPath = $this->getPropertyPath($field, $config->getField($field));
            if (!$entityMetadata->isAssociation($propertyPath)
                || $entityMetadata->isCollectionValuedAssociation($propertyPath)
            ) {
                continue;
            }

            $join = sprintf('%s.%s', $rootAlias, $propertyPath);
            $alias = $this->getExistingJoinAlias($qb, $rootAlias, $join);
            if (!$alias) {
                $alias = 'a' . ++$aliasCounter;
                $qb->leftJoin($join, $alias);
            }
            $this->updateSelectQueryPart(
                $qb,
                $alias,
                $entityMetadata->getAssociationTargetClass($propertyPath),
                $this->getTargetEntity($config, $field),
                true
            );
        }
    }

      /**
     * @param QueryBuilder $qb
     * @param string       $alias
     * @param string       $entityClass
     * @param EntityConfig $config
     * @param bool         $withAssociations
     */
    protected function updateSelectQueryPart(
        QueryBuilder $qb,
        $alias,
        $entityClass,
        EntityConfig $config,
        $withAssociations = false
    ) {
        if ($config->isPartialLoadEnabled()) {
            $fields = $this->fieldAccessor->getFieldsToSelect($entityClass, $config, $withAssociations);
            $qb->addSelect(sprintf('partial %s.{%s}', $alias, implode(',', $fields)));
        } else {
            $qb->addSelect($alias);
        }
    }

    /**
     * @param string           $fieldName
     * @param FieldConfig|null $fieldConfig
     *
     * @return string
     */
    protected function getPropertyPath($fieldName, FieldConfig $fieldConfig = null)
    {
        if (null === $fieldConfig) {
            return $fieldName;
        }

        return $fieldConfig->getPropertyPath($fieldName);
    }

    /**
     * @param EntityConfig $config
     * @param string       $field
     *
     * @return EntityConfig
     */
    protected function getTargetEntity(EntityConfig $config, $field)
    {
        $fieldConfig = $config->getField($field);
        if (null === $fieldConfig) {
            return new InternalEntityConfig();
        }

        $targetConfig = $fieldConfig->getTargetEntity();
        if (null === $targetConfig) {
            $targetConfig = new InternalEntityConfig();
            $fieldConfig->setTargetEntity($targetConfig);
        }

        return $targetConfig;
    }

     /**
     * @param QueryBuilder $qb
     * @param string       $rootAlias
     * @param string       $join
     *
     * @return string|null
     */
    protected function getExistingJoinAlias(QueryBuilder $qb, $rootAlias, $join)
    {
        $joins = $qb->getDQLPart('join');
        if (!empty($joins[$rootAlias])) {
            /** @var Query\Expr\Join $item */
            foreach ($joins[$rootAlias] as $item) {
                if ($item->getJoin() === $join) {
                    return $item->getAlias();
                }
            }
        }

        return null;
    }
}
