<?php

namespace Pintushi\Bundle\TaxonomyBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\TaxonomyBundle\Entity\TaxonInterface;

interface TaxonRepositoryInterface
{
    /**
     * @param string $parentCode
     * @param string|null $locale
     *
     * @return TaxonInterface[]
     */
    public function findChildren(string $parentCode, ?string $locale = null);

    /**
     * @return TaxonInterface[]
     */
    public function findRootNodes();
}
