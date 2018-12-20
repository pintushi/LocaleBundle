<?php

namespace Pintushi\Bundle\SmsBundle\Builder;

use Overtrue\EasySms\Message as BaseMessage;
use Pintushi\Bundle\SmsBundle\Repository\GatewayConfigRepository;
use Overtrue\EasySms\Contracts\GatewayInterface;

class MessageTemplateGatewayConfigBuilder implements MessageTemplateBuilderInterface
{
    private $gatewayConfigRepository;
    private $templateName;

    public function __construct(GatewayConfigRepository $gatewayConfigRepository, $templateName)
    {
        $this->gatewayConfigRepository = $gatewayConfigRepository;
        $this->templateName = $templateName;
    }

    public function getTemplate(GatewayInterface $gateway)
    {
        $gatewayConfig =  $this->gatewayConfigRepository->findOneByName($gateway->getName());

        $templates = $gatewayConfig->getTemplates();

        if (!isset($templates[$this->templateName]) || empty($templates[$this->templateName])) {
            throw new \RuntimeException(sprintf('短信模板%s未设置', $this->templateName));
        }

        return  $templates[$this->templateName];
    }
}
