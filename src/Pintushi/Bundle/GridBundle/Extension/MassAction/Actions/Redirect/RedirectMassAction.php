<?php

namespace Pintushi\Bundle\GridBundle\Extension\MassAction\Actions\Redirect;

use Pintushi\Bundle\GridBundle\Extension\Action\ActionConfiguration;
use Pintushi\Bundle\GridBundle\Extension\MassAction\Actions\AbstractMassAction;

class RedirectMassAction extends AbstractMassAction
{
    /** @var array */
    protected $requiredOptions = ['route'];

    /**
     * {@inheritDoc}
     */
    public function setOptions(ActionConfiguration $options)
    {
        if (empty($options['frontend_handle'])) {
            $options['frontend_handle'] = 'redirect';
        }

        if (empty($options['route_parameters'])) {
            $options['route_parameters'] = [];
        }

        return parent::setOptions($options);
    }
}
