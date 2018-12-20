<?php

namespace Pintushi\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface TaxonsAwareInterface
{
    /**
     * @return Collection|TaxonInterface[]
     */
    public function getTaxons();

    public function hasTaxon(TaxonInterface $taxon): bool;

    public function addTaxon(TaxonInterface $taxon): void;

    public function removeTaxon(TaxonInterface $taxon): void;
}
