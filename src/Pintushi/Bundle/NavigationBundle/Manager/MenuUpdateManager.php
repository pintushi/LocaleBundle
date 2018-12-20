<?php

namespace Pintushi\Bundle\NavigationBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Knp\Menu\ItemInterface;
use Pintushi\Bundle\NavigationBundle\Entity\MenuUpdateInterface;
use Pintushi\Bundle\NavigationBundle\Entity\Repository\MenuUpdateRepository;
use Pintushi\Bundle\NavigationBundle\Exception\NotFoundParentException;
use Pintushi\Bundle\NavigationBundle\JsTree\MenuUpdateTreeHandler;
use Pintushi\Bundle\NavigationBundle\Menu\Helper\MenuUpdateHelper;
use Pintushi\Bundle\NavigationBundle\Utils\MenuUpdateUtils;
use Oro\Bundle\UIBundle\Model\TreeItem;

class MenuUpdateManager
{
    /** @var ManagerRegistry */
    private $managerRegistry;

    /** @var MenuUpdateHelper */
    private $menuUpdateHelper;

    /** @var string */
    private $entityClass;
 
    /**
     * @param ManagerRegistry  $managerRegistry
     * @param MenuUpdateHelper $menuUpdateHelper
     * @param string           $entityClass
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        MenuUpdateHelper $menuUpdateHelper,
        $entityClass
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->menuUpdateHelper = $menuUpdateHelper;
        $this->entityClass = $entityClass;
    }

    /**
     * Create menu update entity
     *
     * @param ItemInterface $menu
     * @param array         $options
     * @return MenuUpdateInterface
     */
    public function createMenuUpdate(ItemInterface $menu, array $options = [])
    {
        /** @var MenuUpdateInterface $entity */
        $entity = new $this->entityClass;

        if (isset($options['key'])) {
            $entity->setKey($options['key']);
        }

        $entity->setMenu($menu->getName());
        if (isset($options['parentKey'])) {
            $parent = $this->findMenuItem($menu, $options['parentKey']);
            if (!$parent) {
                throw new NotFoundParentException(
                    sprintf('Parent with "%s" parentKey not found.', $options['parentKey'])
                );
            }
            $entity->setParentKey($options['parentKey']);
        }

        if (isset($options['isDivider']) && $options['isDivider']) {
            $entity->setDivider(true);
            $entity->setDefaultTitle(MenuUpdateTreeHandler::MENU_ITEM_DIVIDER_LABEL);
            $entity->setUri('#');
        }

        $item = $this->findMenuItem($menu, $entity->getKey());
        if ($item) {
            $entity->setCustom(false);
            MenuUpdateUtils::updateMenuUpdate($entity, $item, $entity->getMenu(), $this->menuUpdateHelper);
        } else {
            $entity->setCustom(true);
        }

        return $entity;
    }

    /**
     * @param string $menuName
     * @param string $key
     * @return null|object|MenuUpdateInterface
     */
    public function findMenuUpdate($menuName, $key)
    {
        return $this->getRepository()->findOneBy(
            [
                'menu' => $menuName,
                'key' => $key,
            ]
        );
    }

    /**
     * Get existing or create new MenuUpdate for specified menu, key
     *
     * @param ItemInterface $menu
     * @param string        $key
     * @return null|MenuUpdateInterface
     *
     */
    public function findOrCreateMenuUpdate(ItemInterface $menu, $key)
    {
        $update = $this->findMenuUpdate($menu->getName(), $key);
        if (null === $update) {
            $update = $this->createMenuUpdate($menu, ['key' => $key]);
        }

        return $update;
    }

    /**
     * @param ItemInterface $menu
     * @param string        $key
     *
     * @return ItemInterface|null
     */
    protected function findMenuItem(ItemInterface $menu, $key)
    {
        if ($menu->getName() === $key) {
            return $menu;
        }

        return MenuUpdateUtils::findMenuItem($menu, $key);
    }

    /**
     * @param ItemInterface $menu
     * @param string        $key
     */
    public function showMenuItem(ItemInterface $menu, $key)
    {
        $item = $this->findMenuItem($menu, $key);
        if ($item !== null) {
            $update = $this->findOrCreateMenuUpdate($menu, $item->getName());
            $update->setActive(true);
            $this->getEntityManager()->persist($update);

            $this->showMenuItemParents($menu, $item);
            $this->showMenuItemChildren($menu, $item);

            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ItemInterface $menu
     * @param ItemInterface $item
     */
    private function showMenuItemParents(ItemInterface $menu, $item)
    {
        $parent = $item->getParent();
        if ($parent !== null && !$parent->isDisplayed()) {
            $update = $this->findOrCreateMenuUpdate($menu, $parent->getName());
            $update->setActive(true);
            $this->getEntityManager()->persist($update);

            $this->showMenuItemParents($menu, $parent);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param ItemInterface $item
     */
    private function showMenuItemChildren(ItemInterface $menu, $item)
    {
        /** @var ItemInterface $child */
        foreach ($item->getChildren() as $child) {
            $update = $this->findOrCreateMenuUpdate($menu, $child->getName());
            $update->setActive(true);
            $this->getEntityManager()->persist($update);

            $this->showMenuItemChildren($menu, $child);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param string        $key
     */
    public function hideMenuItem(ItemInterface $menu, $key)
    {
        $item = $this->findMenuItem($menu, $key);
        if ($item !== null) {
            $update = $this->findOrCreateMenuUpdate($menu, $item->getName());
            $update->setActive(false);
            $this->getEntityManager()->persist($update);

            $this->hideMenuItemChildren($menu, $item);

            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ItemInterface $menu
     * @param ItemInterface $item
     */
    private function hideMenuItemChildren(ItemInterface $menu, ItemInterface $item)
    {
        /** @var ItemInterface $child */
        foreach ($item->getChildren() as $child) {
            $update = $this->findOrCreateMenuUpdate($menu, $child->getName());
            $update->setActive(false);
            $this->getEntityManager()->persist($update);

            $this->hideMenuItemChildren($menu, $child);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param string        $key
     * @param string        $parentKey
     * @param int           $position
     *
     * @return MenuUpdateInterface[]
     */
    public function moveMenuItem(ItemInterface $menu, $key, $parentKey, $position)
    {
        $currentUpdate = $this->findOrCreateMenuUpdate($menu, $key);

        $parent = $this->findMenuItem($menu, $parentKey);
        if ($parent && $parentKey !== $menu->getName()) {
            $currentUpdate->setParentKey($parent->getName());
        } else {
            $currentUpdate->setParentKey(null);
        }

        $order = [];

        $i = 0;
        /** @var ItemInterface $child */
        foreach ($parent->getChildren() as $child) {
            if ($i === (int)$position) {
                $currentUpdate->setPriority($i);
                $i++;
            }

            if ($child->getName() != $key) {
                $order[$i++] = $child;
            }
        }

        $updates = array_merge(
            [$currentUpdate],
            $this->getReorderedMenuUpdates($menu, $order)
        );

        return $updates;
    }

    /**
     * @param ItemInterface $menu
     * @param TreeItem[]    $treeItems
     * @param string        $parentKey
     * @param int           $position
     * @return \Pintushi\Bundle\NavigationBundle\Entity\MenuUpdateInterface[]
     */
    public function moveMenuItems(ItemInterface $menu, $treeItems, $parentKey, $position)
    {
        $parent = $this->findMenuItem($menu, $parentKey);
        $menuUpdates = [];

        $index = 0;
        foreach ($treeItems as $treeItem) {
            $currentUpdate = $this->findOrCreateMenuUpdate($menu, $treeItem->getKey());
            $menuUpdates[] = $currentUpdate;

            if ($menu->getName() !== $parentKey) {
                $currentUpdate->setParentKey($parent ? $parent->getName() : null);
            } else {
                $currentUpdate->setParentKey(null);
            }

            $currentUpdate->setPriority($position + $index);
            $index++;
        }

        $order = [];
        $i = 0;

        /** @var ItemInterface $child */
        foreach ($parent->getChildren() as $child) {
            $newPosition = $i < $position ? $i : $i + count($treeItems);
            $order[$newPosition] = $child;
            $i++;
        }

        return array_merge(
            $menuUpdates,
            $this->getReorderedMenuUpdates($menu, $order)
        );
    }

    /**
     * @param string $menuName
     */
    public function deleteMenuUpdates($menuName = null)
    {
        if ($menuName) {
            $criteria['menu'] = $menuName;
        }

        $menuUpdates = $this->getRepository()->findBy($criteria);

        foreach ($menuUpdates as $menuUpdate) {
            $this->getEntityManager()->remove($menuUpdate);
        }

        $this->getEntityManager()->flush($menuUpdates);
    }

    /**
     * Get list of menu update with new position
     *
     * @param ItemInterface   $menu
     * @param ItemInterface[] $orderedChildren
     *
     * @return MenuUpdateInterface[]
     */
    private function getReorderedMenuUpdates(ItemInterface $menu, $orderedChildren)
    {
        $order = [];
        foreach ($orderedChildren as $priority => $child) {
            $order[$child->getName()] = $priority;
        }

        /** @var MenuUpdateInterface[] $updates */
        $updates = $this->getRepository()->findBy(
            [
                'menu' => $menu->getName(),
                'key' => array_keys($order),
            ]
        );

        foreach ($updates as $update) {
            $update->setPriority($order[$update->getKey()]);
            unset($orderedChildren[$order[$update->getKey()]]);
        }

        foreach ($orderedChildren as $priority => $child) {
            $update = $this->createMenuUpdate(
                $menu,
                ['key' => $child->getName()]
            );
            MenuUpdateUtils::updateMenuUpdate($update, $child, $menu->getName(), $this->menuUpdateHelper);
            $update->setPriority($priority);
            $updates[] = $update;
        }

        return $updates;
    }

    /**
     * @return MenuUpdateRepository|EntityRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->entityClass);
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->managerRegistry->getManagerForClass($this->entityClass);
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
