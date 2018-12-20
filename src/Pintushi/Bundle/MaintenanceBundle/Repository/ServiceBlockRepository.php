<?php

namespace Pintushi\Bundle\MaintenanceBundle\Repository;

use Pintushi\Bundle\MaintenanceBundle\Entity\ServiceBlock;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServiceBlockRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceBlock::class);
    }

    public function findActiveServiceBlocks()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->leftJoin('o.services', 's')
            ->addSelect('s')
            ->andWhere('o.enabled=true')
            ->orderBy('o.position', 'asc');

        return $queryBuilder->getQuery()->getResult();
    }
}
