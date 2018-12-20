<?php

namespace Pintushi\Bundle\CoreBundle\DataFixtures;

use Pintushi\Bundle\SecurityBundle\DataFixtures\AbstractAclData;

class AclDataFixture extends AbstractAclData
{
     /**
     * {@inheritdoc}
     */
    protected function getDataPath()
    {
        return '@PintushiCoreBundle/DataFixtures/Data/acl.yml';
    }
}
