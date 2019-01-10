<?php

namespace Pintushi\Bundle\PaymentBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethod;
use Doctrine\Common\Persistence\ManagerRegistry;

class PaymentMethodRepository extends EntityRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
    }

    public function createActivePaymentMethodsQueryBuilder()
    {
        $qb = $this->createQueryBuilder('o')
        ;

        $this->applyCriteria($qb, ['enabled' => true]);
        $this->applySorting($qb, ['position' => 'asc']);

        return $qb;
    }
}
