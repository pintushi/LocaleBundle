<?php

namespace Pintushi\Bundle\GridBundle\Tools;

use Pintushi\Bundle\GridBundle\Datagrid\ParameterBag;
use Oro\Bundle\FilterBundle\Grid\Extension\AbstractFilterExtension;

/**
 * Contains useful methods common for services that work with datagrid parameters.
 */
class DatagridParametersHelper
{
    public const DATAGRID_SKIP_EXTENSION_PARAM = 'dataGridSkipExtensionParam';

    /**
     * @param ParameterBag $datagridParameters
     * @param string $parameterName
     *
     * @return mixed|null
     */
    public function getFromParameters(ParameterBag $datagridParameters, string $parameterName)
    {
        if ($datagridParameters->has($parameterName)) {
            $parameter = $datagridParameters->get($parameterName);
        }

        return $parameter ?? null;
    }

    /**
     * @param ParameterBag $datagridParameters
     * @param string $parameterName
     *
     * @return mixed|null
     */
    public function getFromMinifiedParameters(ParameterBag $datagridParameters, string $parameterName)
    {
        // Try to fetch from minified parameters if any.
        if ($datagridParameters->has(ParameterBag::MINIFIED_PARAMETERS)) {
            $minifiedParameters = $datagridParameters->get(ParameterBag::MINIFIED_PARAMETERS);
            if (array_key_exists($parameterName, $minifiedParameters)) {
                $parameter = $minifiedParameters[$parameterName];
            }
        }

        return $parameter ?? null;
    }

    /**
     * @param ParameterBag $dataGridParameters
     * @param string $filterName
     */
    public function resetFilter(ParameterBag $dataGridParameters, string $filterName): void
    {
        $filters = $dataGridParameters->get(AbstractFilterExtension::FILTER_ROOT_PARAM);
        if ($filters) {
            unset($filters[$filterName]);
            $dataGridParameters->set(AbstractFilterExtension::FILTER_ROOT_PARAM, $filters);
        }

        $minifiedFilters = $dataGridParameters->get(ParameterBag::MINIFIED_PARAMETERS);
        if ($minifiedFilters) {
            unset($minifiedFilters[AbstractFilterExtension::MINIFIED_FILTER_PARAM][$filterName]);
            $dataGridParameters->set(ParameterBag::MINIFIED_PARAMETERS, $minifiedFilters);
        }
    }

    /**
     * @param ParameterBag $dataGridParameters
     */
    public function resetFilters(ParameterBag $dataGridParameters): void
    {
        $filters = $dataGridParameters->get(AbstractFilterExtension::FILTER_ROOT_PARAM);
        if ($filters) {
            $dataGridParameters->set(AbstractFilterExtension::FILTER_ROOT_PARAM, []);
        }

        $minifiedFilters = $dataGridParameters->get(ParameterBag::MINIFIED_PARAMETERS);
        if ($minifiedFilters) {
            $minifiedFilters[AbstractFilterExtension::MINIFIED_FILTER_PARAM] = [];
            $dataGridParameters->set(ParameterBag::MINIFIED_PARAMETERS, $minifiedFilters);
        }
    }

    /**
     * @param ParameterBag $parameterBag
     * @param boolean $value
     */
    public function setDatagridExtensionSkipped(ParameterBag $parameterBag, bool $value = true): void
    {
        $parameterBag->set(self::DATAGRID_SKIP_EXTENSION_PARAM, $value);
    }

    /**
     * @param ParameterBag $parameterBag
     * @return bool
     */
    public function isDatagridExtensionSkipped(ParameterBag $parameterBag): bool
    {
        return $parameterBag->get(self::DATAGRID_SKIP_EXTENSION_PARAM, false);
    }
}
