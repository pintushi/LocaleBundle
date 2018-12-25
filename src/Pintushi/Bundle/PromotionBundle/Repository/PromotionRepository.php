<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionRepositoryInterface;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\PromotionBundle\Entity\Promotion;

class PromotionRepository extends ServiceEntityRepository implements PromotionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findActive(): array
    {
        return $this->filterByActive($this->createQueryBuilder('o'))
            ->addOrderBy('o.priority', 'desc')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findByName(string $name): array
    {
        return $this->findBy(['name' => $name]);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param \DateTimeInterface|null $date
     *
     * @return QueryBuilder
     */
    protected function filterByActive(QueryBuilder $queryBuilder, ?\DateTimeInterface $date = null): QueryBuilder
    {
        return $queryBuilder
            ->andWhere('o.startsAt IS NULL OR o.startsAt < :date')
            ->andWhere('o.endsAt IS NULL OR o.endsAt > :date')
            ->setParameter('date', $date ?: new \DateTime())
        ;
    }
}
