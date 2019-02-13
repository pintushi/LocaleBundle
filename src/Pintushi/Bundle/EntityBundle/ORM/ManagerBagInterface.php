<?php

namespace Pintushi\Bundle\EntityBundle\ORM;

use Doctrine\Common\Persistence\ObjectManager;

interface ManagerBagInterface
{
    /**
     * Gets all managers that may contain entities.
     *
     * @return ObjectManager[]
     */
    public function getManagers();
}
