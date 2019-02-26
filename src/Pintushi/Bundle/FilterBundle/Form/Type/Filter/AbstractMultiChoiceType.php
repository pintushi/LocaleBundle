<?php

namespace Pintushi\Bundle\FilterBundle\Form\Type\Filter;

use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractMultiChoiceType extends AbstractChoiceType
{
    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }
}
