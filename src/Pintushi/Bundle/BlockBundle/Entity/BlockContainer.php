<?php

namespace  Pintushi\Bundle\BlockBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="blockContainer",
 *      },
 *    "ownership"={
 *           "owner_type"="ORGANIZATION",
 *           "owner_field_name"="organization",
 *           "owner_column_name"="organization_id",
 *      },
 *  }
 * )
 */
class BlockContainer implements OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    protected $id;

    protected $title;

    protected $name;

    protected $blocks;

    public function __construct()
    {
        $this->blocks= new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

  /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $title
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    public function addBlock(Block $block)
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks->add($block);
        }

        $block->setBlockContainer($this);

        return $this;
    }

    public function removeBlock($block)
    {
        if ($this->blocks->contains($block)) {
            $this->blocks->removeElement($block);
        }

        return $this;
    }
}
