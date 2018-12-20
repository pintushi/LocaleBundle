<?php

namespace Pintushi\Bundle\SmsBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pintushi\Bundle\SmsBundle\PintushiSmsEvents;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class VerificationCodeListener implements EventSubscriberInterface
{
    protected $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            PintushiSmsEvents::SMS_VERIFICATION_CODE_PRE_SEND => 'preSend',
            PintushiSmsEvents::SMS_VERIFICATION_CODE_POST_SEND => 'postSend',
        );
    }

    public function postSend(GenericEvent $event)
    {
    }

    public function preSend(GenericEvent $event)
    {
    }
}
