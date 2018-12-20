<?php

namespace Pintushi\Bundle\NavigationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Pintushi\Bundle\NavigationBundle\Model\ExtendMenuUpdate;

/**
 * @ORM\Entity(repositoryClass="Pintushi\Bundle\NavigationBundle\Entity\Repository\MenuUpdateRepository")
 * @ORM\Table(
 *      name="pintushi_navigation_menu_update",
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class MenuUpdate  implements
    MenuUpdateInterface
{
    use MenuUpdateTrait;

    /**
     * {@inheritdoc}
     */
    public function getExtras()
    {
        $extras = [
            'divider' => $this->isDivider(),
            'translate_disabled' => $this->getId() ? true : false
        ];

        if ($this->getPriority() !== null) {
            $extras['position'] = $this->getPriority();
        }

        if ($this->getIcon() !== null) {
            $extras['icon'] = $this->getIcon();
        }

        return $extras;
    }
}
