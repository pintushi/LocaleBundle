<?php

namespace Pintushi\Bundle\SmsBundle\Entity;

use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableInterface;

/**
 * @Config(
 *  defaultValues={
 *    "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="promotion",
 *      },
 *    "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *     },
 *  }
 * )
 */
class GatewayConfig implements OrganizationAwareInterface, ToggleableInterface
{
    use OrganizationAwareTrait, ToggleableTrait;

    private $id;

    private $gateway;

    private $configuration = [];

    private $priority = 0;

    private $templates = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     *
     * @return self
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param mixed $configuration
     *
     * @return self
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     *
     * @return self
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param mixed $templates
     *
     * @return self
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;

        return $this;
    }
}
