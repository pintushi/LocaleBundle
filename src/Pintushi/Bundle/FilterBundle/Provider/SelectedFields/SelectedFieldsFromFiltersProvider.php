<?php

namespace Pintushi\Bundle\FilterBundle\Provider\SelectedFields;

use Pintushi\Bundle\GridBundle\Datagrid\Common\DatagridConfiguration;
use Pintushi\Bundle\GridBundle\Provider\SelectedFields\AbstractSelectedFieldsProvider;
use Pintushi\Bundle\FilterBundle\Grid\Extension\Configuration as FilterConfiguration;

/**
 * Returns fields (required by applied filters) which must be present in select statement of datasource query.
 */
class SelectedFieldsFromFiltersProvider extends AbstractSelectedFieldsProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(DatagridConfiguration $datagridConfiguration): array
    {
        return $datagridConfiguration->offsetGetByPath(FilterConfiguration::COLUMNS_PATH, []);
    }
}
