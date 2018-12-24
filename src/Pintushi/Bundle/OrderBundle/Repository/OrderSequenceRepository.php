<?php

namespace Pintushi\Bundle\OrderBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\OrderBundle\Entity\OrderSequence;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;

class OrderSequenceRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderSequence::class);
    }
}
