<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ShippingBundle\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\ShippingBundle\Entity\ShippingMethod;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;

class ShippingMethodRepository extends EntityRepository implements ShippingMethodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShippingMethod::class);
    }

    public function findActiveShippingMethods()
    {
        $qb = $this->createQueryBuilder('o')
        ;

        $this->applyCriteria($qb, ['enabled' => true]);
        $this->applySorting($qb, ['position' => 'asc']);

        return $qb->getQuery()->getResult();
    }
}
