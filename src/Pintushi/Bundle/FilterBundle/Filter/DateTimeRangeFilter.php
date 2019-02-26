<?php

namespace Pintushi\Bundle\FilterBundle\Filter;

use Pintushi\Bundle\FilterBundle\Form\Type\Filter\DateTimeRangeFilterType;

/**
 * The filter for "datetime" fields.
 */
class DateTimeRangeFilter extends AbstractDateFilter
{
    /**
     * {@inheritdoc}
     */
    protected function getFormType()
    {
        return DateTimeRangeFilterType::class;
    }
}
