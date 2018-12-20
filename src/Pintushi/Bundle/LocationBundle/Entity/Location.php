<?php

namespace Pintushi\Bundle\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Location
{
    const LEVEL_PROVICE = 1;
    const LEVEL_CITY = 2;
    const LEVEL_REGION = 3;

    protected $parent;

    protected $level;

    protected $id;

    protected $name;

    protected $left;

    protected $right;

    protected $abbr = null;

    protected $code;

    protected $postCode;

    protected $areaCode;

    protected $pinyin;

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     */
    public function setPostCode($postCode): void
    {
        $this->postCode = $postCode;
    }

    /**
     * @return mixed
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

    /**
     * @param mixed $areaCode
     */
    public function setAreaCode($areaCode): void
    {
        $this->areaCode = $areaCode;
    }

    /**
     * @return mixed
     */
    public function getPinyin()
    {
        return $this->pinyin;
    }

    /**
     * @param mixed $pinyin
     */
    public function setPinyin($pinyin): void
    {
        $this->pinyin = $pinyin;
    }


    /**
     * @return mixed
     */
    public function getAbbr()
    {
        return $this->abbr;
    }

    /**
     * @param mixed $abbr
     */
    public function setAbbr($abbr): void
    {
        $this->abbr = $abbr;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param mixed $left
     */
    public function setLeft($left): void
    {
        $this->left = $left;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $right
     */
    public function setRight($right): void
    {
        $this->right = $right;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    protected $children;

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }


    public function hasChildren()
    {
        return !$this->children->isEmpty();
    }


    public function addChild(Location $location): void
    {
        if (!$this->hasChild($location)) {
            $location->setParent($this);
            $this->children->add($location);
        }
    }

    public function removeChild(Location $location): void
    {
        if ($this->hasChild($location)) {
            $location->setParent(null);
            $this->children->removeElement($location);
        }
    }

    public function hasChild(Location $location)
    {
        return $this->children->contains($location);
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }
}
