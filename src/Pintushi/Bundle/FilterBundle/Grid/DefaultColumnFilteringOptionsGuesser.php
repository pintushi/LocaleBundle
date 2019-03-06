<?php

namespace Pintushi\Bundle\FilterBundle\Grid;

use Pintushi\Bundle\GridBundle\Grid\AbstractColumnOptionsGuesser;
use Pintushi\Bundle\GridBundle\Grid\Guess\ColumnGuess;
use Pintushi\Bundle\FilterBundle\Form\Type\Filter\NumberFilterType;

class DefaultColumnFilteringOptionsGuesser extends AbstractColumnOptionsGuesser
{
    /**
     * {@inheritdoc}
     */
    public function guessFilter($class, $property, $type)
    {
        switch ($type) {
            case 'integer':
            case 'smallint':
            case 'bigint':
                $options = [
                    'type'    => 'number-range',
                    'options' => [
                        'data_type' => NumberFilterType::DATA_INTEGER
                    ]
                ];
                break;
            case 'decimal':
            case 'float':
                $options = [
                    'type'    => 'number-range',
                    'options' => [
                        'data_type' => NumberFilterType::DATA_DECIMAL
                    ]
                ];
                break;
            case 'boolean':
                $options = [
                    'type' => 'boolean'
                ];
                break;
            case 'date':
                $options = [
                    'type' => 'date'
                ];
                break;
            case 'datetime':
                $options = [
                    'type' => 'datetime'
                ];
                break;
            case 'money':
                $options = [
                    'type' => 'number-range'
                ];
                break;
            case 'percent':
                $options = [
                    'type' => 'percent'
                ];
                break;
            default:
                $options = [
                    'type' => 'string'
                ];
                break;
        }

        return new ColumnGuess($options, ColumnGuess::LOW_CONFIDENCE);
    }
}
