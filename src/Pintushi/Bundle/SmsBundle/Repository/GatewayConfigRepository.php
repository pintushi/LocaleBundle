<?php

namespace Pintushi\Bundle\SmsBundle\Repository;

use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\SmsBundle\Entity\GatewayConfig;
use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationInterface;

class GatewayConfigRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GatewayConfig::class);
    }

    public function findActiveGatewaysByOrganization(OrganizationInterface $organization)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
               ->andWhere('IDENTITY(o.organization) = :organizationId')
               ->setParameter('organizationId', $organization->getId())
               ->andWhere('o.enabled =:enabled')
               ->setParameter('enabled', true)
               ->orderBy('o.priority')
               ->getQuery()
               ->getResult()
        ;
    }
}
