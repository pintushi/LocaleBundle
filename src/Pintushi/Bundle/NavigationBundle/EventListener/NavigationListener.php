<?php

namespace Pintushi\Bundle\NavigationBundle\EventListener;

use Pintushi\Bundle\NavigationBundle\Event\ConfigureMenuEvent;
use Pintushi\Bundle\NavigationBundle\Utils\MenuUpdateUtils;
use Pintushi\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NavigationListener
{
    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TokenAccessorInterface        $tokenAccessor
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenAccessorInterface $tokenAccessor
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenAccessor = $tokenAccessor;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onNavigationConfigure(ConfigureMenuEvent $event)
    {
        if (!$this->tokenAccessor->hasUser()) {
            return;
        }

        $manageMenusItem = MenuUpdateUtils::findMenuItem($event->getMenu(), 'menu_list_default');
        if (null !== $manageMenusItem
            && (
                !$this->authorizationChecker->isGranted('pintushi_config_system')
                || !$this->authorizationChecker->isGranted('pintushi_navigation_manage_menus')
            )
        ) {
            $manageMenusItem->setDisplay(false);
        }
    }
}
