<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Entity;

use Payum\Core\Model\GatewayConfig as BaseGatewayConfig;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="payum",
 *      },
 *    "ownership"={
 *           "owner_type"="ORGANIZATION",
 *           "owner_field_name"="organization",
 *           "owner_column_name"="organization_id",
 *      },
 *  }
 * )
 */
class GatewayConfig extends BaseGatewayConfig implements OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
