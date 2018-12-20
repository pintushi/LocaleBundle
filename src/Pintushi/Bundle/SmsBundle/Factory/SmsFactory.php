<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Factory;

use Overtrue\EasySms\EasySms;
use Pintushi\Bundle\OrgResolverBundle\Context\OrganizationContextInterface;
use Pintushi\Bundle\SmsBundle\Repository\GatewayConfigRepository;
use Pintushi\Bundle\OrganizationBundle\Exception\OrganizationNotFoundException;
use Overtrue\EasySms\Strategies\OrderStrategy;

class SmsFactory
{
    private $organizationContext;
    private $gatewayConfigRepository;

    public function __construct(
        OrganizationContextInterface $organizationContext,
        GatewayConfigRepository $gatewayConfigRepository
    ) {
        $this->organizationContext = $organizationContext;
        $this->gatewayConfigRepository = $gatewayConfigRepository;
    }

    public function create()
    {
        $organization = $this->organizationContext->getOrganization();
        if (!$organization) {
            throw new OrganizationNotFoundException();
        }

        $gatewayConfigs = $this->gatewayConfigRepository->findActiveGatewaysByOrganization($organization);

        return new EasySms($this->buildGatewayConfig($gatewayConfigs));
    }

    protected function buildGatewayConfig(array $gatewayConfigs)
    {
        return [
            'timeout' => 5.0,
            'default' => [
                'strategy' => OrderStrategy::class,
                'gateways' =>  array_map(function ($gateway) {
                    return $gateway->getGateway();
                }, $gatewayConfigs),
            ],
            'gateways' => array_map(function ($gateway) {
                    return [$gateway->getGateway() => $gateway->getConfiguration()];
            }, $gatewayConfigs)
        ];
    }
}
