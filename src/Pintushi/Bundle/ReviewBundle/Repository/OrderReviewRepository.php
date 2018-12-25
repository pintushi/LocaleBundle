<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\ReviewBundle\Entity\OrderReview;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;

class OrderReviewRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderReview::class);
    }
}
