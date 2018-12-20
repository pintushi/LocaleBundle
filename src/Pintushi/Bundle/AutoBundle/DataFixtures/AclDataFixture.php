<?php

namespace Pintushi\Bundle\AutoBundle\DataFixtures;

use Pintushi\Bundle\SecurityBundle\DataFixtures\AbstractAclData;

class AclDataFixture extends AbstractAclData
{
     /**
     * {@inheritdoc}
     */
    protected function getDataPath()
    {
        return '@PintushiAutoBundle/DataFixtures/Data/acl.yml';
    }
}
