<?php

namespace Pintushi\Bundle\FilterBundle\Filter;

use Pintushi\Bundle\FilterBundle\Datasource\FilterDatasourceAdapterInterface;
use Pintushi\Bundle\FilterBundle\Form\Type\Filter\ChoiceFilterType;

class SingleChoiceFilter extends ChoiceFilter
{
    /**
     * {@inheritdoc}
     */
    protected function buildComparisonExpr(
        FilterDatasourceAdapterInterface $ds,
        $comparisonType,
        $fieldName,
        $parameterName
    ) {
        switch ($comparisonType) {
            case ChoiceFilterType::TYPE_NOT_CONTAINS:
                return $ds->expr()->neq($fieldName, $parameterName, true);
            default:
                return $ds->expr()->eq($fieldName, $parameterName, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parseData($data)
    {
        if (!is_array($data)
            || !array_key_exists('value', $data)
            || !$data['value']
        ) {
            return false;
        }

        return $data;
    }
}
