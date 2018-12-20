<?php

namespace Pintushi\Bundle\SmsBundle\Builder;

use Overtrue\EasySms\Contracts\GatewayInterface;

interface MessageTemplateBuilderInterface
{
    public function getTemplate(GatewayInterface $gateway);
}
