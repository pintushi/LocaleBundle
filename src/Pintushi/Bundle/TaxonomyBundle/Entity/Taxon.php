<?php

namespace Pintushi\Bundle\TaxonomyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="taxon",
 *      },
 *    "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *          },
 *  }
 * )
 */
class Taxon implements TaxonInterface, OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    private $name;

    private $description;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var TaxonInterface
     */
    protected $root;

    /**
     * @var TaxonInterface
     */
    protected $parent;

    /**
     * @var Collection|TaxonInterface[]
     */
    protected $children;

    /**
     * @var int
     */
    protected $left;

    /**
     * @var int
     */
    protected $right;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var int
     */
    protected $position;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getTranslation()->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isRoot(): bool
    {
        return null === $this->parent;
    }

    public function getRoot(): ?TaxonInterface
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?TaxonInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(?TaxonInterface $parent = null): void
    {
        $this->parent = $parent;
        if (null !== $parent) {
            $parent->addChild($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParents()
    {
        if (null === $parent = $this->getParent()) {
            return [];
        }

        $parents = [$parent];

        while (null !== $parent->getParent()) {
            $parents[] = $parent = $parent->getParent();
        }

        return $parents;
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestors(): Collection
    {
        $ancestors = [];

        for ($ancestor = $this->getParent(); null !== $ancestor; $ancestor = $ancestor->getParent()) {
            $ancestors[] = $ancestor;
        }

        return new ArrayCollection($ancestors);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChild(TaxonInterface $taxon): bool
    {
        return $this->children->contains($taxon);
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(TaxonInterface $taxon): void
    {
        if (!$this->hasChild($taxon)) {
            $this->children->add($taxon);
        }

        if ($this !== $taxon->getParent()) {
            $taxon->setParent($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(TaxonInterface $taxon): void
    {
        if ($this->hasChild($taxon)) {
            $taxon->setParent(null);

            $this->children->removeElement($taxon);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    public function setLeft(int $left): void
    {
        $this->left = $left;
    }

    /**
     * {@inheritdoc}
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    public function setRight(int $right): void
    {
        $this->right = $right;
    }

    /**
     * {@inheritdoc}
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
