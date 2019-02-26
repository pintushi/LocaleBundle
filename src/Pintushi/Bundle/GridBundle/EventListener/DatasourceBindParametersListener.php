<?php

namespace Pintushi\Bundle\GridBundle\EventListener;

use Pintushi\Bundle\GridBundle\Datagrid\Common\DatagridConfiguration;
use Pintushi\Bundle\GridBundle\Datasource\Orm\ParameterBinder;
use Pintushi\Bundle\GridBundle\Datasource\ParameterBinderAwareInterface;
use Pintushi\Bundle\GridBundle\Event\BuildAfter;

/**
 * Binds datagrid parameters to datasource from datasource option "bind_parameters".
 *
 * @see ParameterBinder
 */
class DatasourceBindParametersListener
{
    /**
     * Binds datagrid parameters to datasource query on event.
     *
     * @param BuildAfter $event
     */
    public function onBuildAfter(BuildAfter $event)
    {
        $datagrid = $event->getDatagrid();

        $datasource = $datagrid->getDatasource();
        if (!$datasource instanceof ParameterBinderAwareInterface) {
            return;
        }

        $parameters = $datagrid->getConfig()
            ->offsetGetByPath(DatagridConfiguration::DATASOURCE_BIND_PARAMETERS_PATH, []);
        if (!$parameters || !is_array($parameters)) {
            return;
        }

        $datasource->bindParameters($parameters);
    }
}
