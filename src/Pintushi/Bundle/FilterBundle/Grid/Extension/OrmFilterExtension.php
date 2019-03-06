<?php

namespace Pintushi\Bundle\FilterBundle\Grid\Extension;

use Pintushi\Bundle\GridBundle\Grid\Common\GridConfiguration;
use Pintushi\Bundle\GridBundle\Datasource\DatasourceInterface;
use Pintushi\Bundle\GridBundle\Datasource\Orm\OrmDatasource;
use Pintushi\Bundle\FilterBundle\Datasource\Orm\OrmFilterDatasourceAdapter;

/**
 * Applies filters to orm datasource.
 * {@inheritDoc}
 */
class OrmFilterExtension extends AbstractFilterExtension
{
    /**
     * {@inheritDoc}
     */
    public function isApplicable(GridConfiguration $config)
    {
        return
            parent::isApplicable($config)
            && $config->isOrmDatasource()
            && null !== $config->offsetGetByPath(Configuration::COLUMNS_PATH);
    }

    /**
     * {@inheritDoc}
     */
    public function visitDatasource(GridConfiguration $config, DatasourceInterface $datasource)
    {
        $filters = $this->getFiltersToApply($config);
        $filtersState = $this->filtersStateProvider->getStateFromParameters($config, $this->getParameters());

        $datasourceAdapter = new OrmFilterDatasourceAdapter($datasource->getQueryBuilder());

        foreach ($filters as $filter) {
            $value = $filtersState[$filter->getName()] ?? null;
            if ($value === null) {
                continue;
            }

            $filterForm = $this->submitFilter($filter, $value);
            if (!$filterForm->isValid()) {
                continue;
            }

            $data = $filterForm->getData();

            // Initially added in AEIV-405 to make work date interval filters.
            if (isset($value['value']['start'])) {
                $data['value']['start_original'] = $value['value']['start'];
            }

            if (isset($value['value']['end'])) {
                $data['value']['end_original'] = $value['value']['end'];
            }

            // Applies filter to datasource.
            $filter->apply($datasourceAdapter, $data);
        }
    }
}
