<?php

namespace Pintushi\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface TaxonInterface
{
    public function isRoot(): bool;

    public function getRoot(): ?TaxonInterface;

    public function getParent(): ?TaxonInterface;

    public function setParent(?TaxonInterface $taxon = null): void;

    public function getAncestors(): Collection;

    /**
     * @return TaxonInterface[]
     */
    public function getParents();

    /**
     * @return Collection|TaxonInterface[]
     */
    public function getChildren();

    public function hasChild(TaxonInterface $taxon): bool;

    public function hasChildren(): bool;

    public function addChild(TaxonInterface $taxon): void;

    public function removeChild(TaxonInterface $taxon): void;

    public function getLeft(): int;

    public function setLeft(int $left): void;

    public function getRight(): int;

    public function setRight(int $right): void;

    public function getLevel(): int;

    public function setLevel(int $level): void;

    public function getPosition(): ?int;

    public function setPosition(int $position): void;
}
