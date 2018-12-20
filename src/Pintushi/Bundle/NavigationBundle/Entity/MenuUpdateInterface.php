<?php

namespace Pintushi\Bundle\NavigationBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface MenuUpdateInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     *
     * @return MenuUpdateInterface
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getParentKey();

    /**
     * @param string $parentKey
     *
     * @return MenuUpdateInterface
     */
    public function setParentKey($parentKey);

    /**
     * @return string
     */
    public function getUri();

    /**
     * @param string $uri
     *
     * @return MenuUpdateInterface
     */
    public function setUri($uri);

    /**
     * @return string
     */
    public function getMenu();

    /**
     * @param string $menu
     *
     * @return MenuUpdateInterface
     */
    public function setMenu($menu);

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param boolean $active
     *
     * @return MenuUpdateInterface
     */
    public function setActive($active);

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @param int $priority
     *
     * @return MenuUpdateInterface
     */
    public function setPriority($priority);

    /**
     * @return boolean
     */
    public function isDivider();

    /**
     * @param boolean $divider
     *
     * @return MenuUpdateInterface
     */
    public function setDivider($divider);

    /**
     * @return boolean
     */
    public function isCustom();

    /**
     * Check is new created item or it's update on existed item
     *
     * @param boolean $custom
     *
     * @return MenuUpdateInterface
     */
    public function setCustom($custom);

    /**
     * Get array of extra data that is not declared in MenuUpdateInterface model
     *
     * @return array
     */
    public function getExtras();
}
