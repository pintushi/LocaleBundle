<?php

namespace Pintushi\Bundle\SmsBundle\Builder;

use Overtrue\EasySms\Message as BaseMessage;
use Pintushi\Bundle\SmsBundle\Repository\GatewayConfigRepository;
use Overtrue\EasySms\Contracts\GatewayInterface;

class MessageTemplateConfigurationBuilder implements MessageTemplateBuilderInterface
{
    private $gatewayConfigRepository;
    private $templateName;

    public function __construct($templates, $templateName)
    {
        $this->templateName = $templateName;
    }

    public function getTemplate(GatewayInterface $gateway)
    {
        $templates = $this->templates;
        if (!isset($templates[$this->templateName]) || empty($templates[$this->templateName])) {
            throw new \RuntimeException(sprintf('短信模板%s未设置', $this->templateName));
        }

        return  $templates[$this->templateName];
    }
}
