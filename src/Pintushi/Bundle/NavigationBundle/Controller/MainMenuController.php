<?php

namespace Pintushi\Bundle\NavigationBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Menu\Util\MenuManipulator;
use Symfony\Component\Routing\Annotation\Route;

class MainMenuController extends MenuController
{
    /**
     * @Route(
     *     name="pintushi_menu_index",
     *     path="/menu",
     *     methods={"GET"}
     * )
     */
    public function index(MenuManipulator $menuManipulator)
    {
        $menu = $this->getMenu('application_menu');

        return  new JsonResponse($menuManipulator->toArray($menu), 200);
    }
}
