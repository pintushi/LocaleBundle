<?php

namespace Pintushi\Bundle\BlockBundle\Repository;

use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\BlockBundle\Entity\BlockContainer;

class BlockContainerRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockContainer::class);
    }

    public function findOneByName($name)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->leftJoin('o.blocks', 'b')
            ->addSelect('b')
            ->andWhere('o.name=:name')
            ->setParameter('name', $name)
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}