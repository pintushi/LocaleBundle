<?php

namespace Pintushi\Bundle\FilterBundle\Provider\State;

use Pintushi\Bundle\GridBundle\Grid\Common\GridConfiguration;
use Pintushi\Bundle\GridBundle\Grid\ParameterBag;
use Pintushi\Bundle\GridBundle\Entity\Manager\GridViewManager;
use Pintushi\Bundle\GridBundle\Provider\State\AbstractStateProvider;
use Pintushi\Bundle\GridBundle\Tools\GridParametersHelper;
use Pintushi\Bundle\FilterBundle\Grid\Extension\AbstractFilterExtension;
use Pintushi\Bundle\FilterBundle\Grid\Extension\Configuration as FilterConfiguration;
use Pintushi\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;

/**
 * Provides request- and user-specific grid state for filters component.
 * Tries to fetch state from grid parameters, then fallbacks to state from current grid view, then from default
 * grid view, then to grid columns configuration.
 * State is respresented by an array with filters names as key and filter parameters array as value.
 */
class FiltersStateProvider extends AbstractStateProvider
{
    /** @var GridParametersHelper */
    private $gridParametersHelper;

    /**
     * @param GridViewManager $gridViewManager
     * @param TokenAccessorInterface $tokenAccessor
     * @param GridParametersHelper $gridParametersHelper
     */
    public function __construct(
        GridViewManager $gridViewManager,
        TokenAccessorInterface $tokenAccessor,
        GridParametersHelper $gridParametersHelper
    ) {
        parent::__construct($gridViewManager, $tokenAccessor);
        $this->gridParametersHelper = $gridParametersHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(GridConfiguration $gridConfiguration, ParameterBag $gridParameters): array
    {
        $state = [];
        $defaultState = $this->getDefaultFiltersState($gridConfiguration);

        // Fetch state from grid parameters.
        $stateFromParameters = $this->getFromParameters($gridParameters);
        if ($stateFromParameters) {
            $state = array_replace($defaultState, $stateFromParameters);
        }

        // Try to fetch state from grid view.
        if (!$state) {
            $gridView = $this->getActualGridView($gridConfiguration, $gridParameters);
            if ($gridView) {
                $state = $gridView->getFiltersData();
            }
        }

        // Fallback to default filters.
        if (!$state) {
            $state = $defaultState;
        }

        return $this->sanitizeState($state, $this->getFiltersConfig($gridConfiguration));
    }

    /**
     * {@inheritdoc}
     */
    public function getStateFromParameters(
        GridConfiguration $gridConfiguration,
        ParameterBag $gridParameters
    ): array {
        $defaultState = $this->getDefaultFiltersState($gridConfiguration);

        // Fetch state from grid parameters.
        $stateFromParameters = $this->getFromParameters($gridParameters);
        $state = array_replace($defaultState, $stateFromParameters);

        return $this->sanitizeState($state, $this->getFiltersConfig($gridConfiguration));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultState(GridConfiguration $gridConfiguration): array
    {
        $state = $this->getDefaultFiltersState($gridConfiguration);

        return $this->sanitizeState($state, $this->getFiltersConfig($gridConfiguration));
    }

    /**
     * @param array $state
     * @param array $filtersConfig
     *
     * @return array
     */
    private function sanitizeState(array $state, array $filtersConfig): array
    {
        // Remove filters which are not in grid configuration.
        $state = array_filter(
            $state,
            function (string $filterName) use ($filtersConfig) {
                if (isset($filtersConfig[$filterName])) {
                    return true;
                }

                // Allows filters with special key - "__{$filterName}".
                // Initially was added to AbstractFilterExtension::updateFilterStateEnabled() in scope of CRM-4760.
                if (strpos($filterName, '__') === 0) {
                    $originalFilterName = substr($filterName, 2);
                    return isset($filtersConfig[$originalFilterName]);
                }

                return false;
            },
            ARRAY_FILTER_USE_KEY
        );

        return $state;
    }

    /**
     * {@inheritdoc}
     */
    private function getFromParameters(ParameterBag $gridParameters): array
    {
        $filtersState = (array) $this->gridParametersHelper
            ->getFromParameters($gridParameters, AbstractFilterExtension::FILTER_ROOT_PARAM);
        $minifiedFiltersState = (array) $this->gridParametersHelper
            ->getFromMinifiedParameters($gridParameters, AbstractFilterExtension::MINIFIED_FILTER_PARAM);

        return array_replace_recursive($filtersState, $minifiedFiltersState);
    }

    /**
     * @param GridConfiguration $gridConfiguration
     *
     * @return array
     */
    private function getFiltersConfig(GridConfiguration $gridConfiguration)
    {
        return (array)$gridConfiguration->offsetGetByPath(FilterConfiguration::COLUMNS_PATH, []);
    }

    /**
     * @param GridConfiguration $gridConfiguration
     *
     * @return array
     */
    private function getDefaultFiltersState(GridConfiguration $gridConfiguration): array
    {
        return $gridConfiguration->offsetGetByPath(FilterConfiguration::DEFAULT_FILTERS_PATH, []);
    }
}
