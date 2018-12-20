<?php

namespace Pintushi\Bundle\NavigationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

trait MenuUpdateTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=100)
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="parent_key", type="string", length=100, nullable=true)
     */
    protected $parentKey;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=1023, nullable=true)
     */
    protected $uri;

    /**
     * @var string
     *
     * @ORM\Column(name="menu", type="string", length=100)
     */
    protected $menu;


    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=150, nullable=true)
     */
    protected $icon;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $active = true;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    protected $priority;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_divider", type="boolean")
     */
    protected $divider = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_custom", type="boolean")
     */
    protected $custom = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return MenuUpdateInterface
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentKey()
    {
        return $this->parentKey;
    }

    /**
     * @param string $parentKey
     *
     * @return MenuUpdateInterface
     */
    public function setParentKey($parentKey)
    {
        $this->parentKey = $parentKey;

        return $this;
    }

    /**
     * @param LocalizedFallbackValue $title
     *
     * @return MenuUpdateInterface
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return MenuUpdateInterface
     */
    public function setDescription($description)
    {
       $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return MenuUpdateInterface
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param string $menu
     *
     * @return MenuUpdateInterface
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return MenuUpdateInterface
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }


    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     *
     * @return MenuUpdateInterface
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return MenuUpdateInterface
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDivider()
    {
        return $this->divider;
    }

    /**
     * @param boolean $divider
     *
     * @return MenuUpdateInterface
     */
    public function setDivider($divider)
    {
        $this->divider = $divider;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if ($this->key === null) {
            $this->key = $this->generateKey();
        }
    }

    /**
     * @return string
     */
    private function generateKey()
    {
        return uniqid('menu_item_');
    }

    /**
     * @return boolean
     */
    public function isCustom()
    {
        return $this->custom;
    }

    /**
     * @param boolean $custom
     *
     * @return MenuUpdateInterface
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;

        return $this;
    }
}
