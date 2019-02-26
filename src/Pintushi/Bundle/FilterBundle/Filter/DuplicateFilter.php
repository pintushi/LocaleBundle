<?php

namespace Pintushi\Bundle\FilterBundle\Filter;

use Pintushi\Bundle\FilterBundle\Datasource\FilterDatasourceAdapterInterface;
use Pintushi\Bundle\FilterBundle\Datasource\Orm\OrmFilterDatasourceAdapter;
use Pintushi\Bundle\FilterBundle\Form\Type\Filter\BooleanFilterType;

class DuplicateFilter extends BooleanFilter
{
    /**
     * Build an expression used to filter data
     *
     * @param FilterDatasourceAdapterInterface $ds
     * @param int                              $comparisonType 0 to compare with false, 1 to compare with true
     * @param string                           $fieldName
     * @return string
     */
    protected function buildComparisonExpr(
        FilterDatasourceAdapterInterface $ds,
        $comparisonType,
        $fieldName
    ) {
        if (!$ds instanceof OrmFilterDatasourceAdapter) {
            throw new \InvalidArgumentException(sprintf(
                '"Pintushi\Bundle\FilterBundle\Datasource\Orm\OrmFilterDatasourceAdapter" expected but "%s" given.',
                get_class($ds)
            ));
        }

        $operator = $comparisonType === BooleanFilterType::TYPE_YES ? '>' : '=';

        $qb = clone $ds->getQueryBuilder();
        $qb
            ->resetDqlPart('orderBy')
            ->resetDqlPart('where')
            ->select($fieldName)
            ->groupBy($fieldName)
            ->having(sprintf('COUNT(%s) %s 1', $fieldName, $operator));
        list($dql) = $this->createDQLWithReplacedAliases($ds, $qb);

        return $ds->expr()->in($fieldName, $dql);
    }
}
