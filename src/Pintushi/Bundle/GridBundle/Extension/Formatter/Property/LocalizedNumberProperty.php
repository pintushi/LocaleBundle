<?php

namespace Pintushi\Bundle\GridBundle\Extension\Formatter\Property;

use Pintushi\Bundle\LocaleBundle\Formatter\NumberFormatter;
use Symfony\Component\Translation\TranslatorInterface;

class LocalizedNumberProperty extends AbstractLocalizedProperty
{
    /** @var $formatter */
    protected $formatter;

    public function __construct(TranslatorInterface $translator, NumberFormatter $formatter)
    {
        parent::__construct($translator);
        $this->formatter = $formatter;
    }

    /**
     * @return NumberFormatter
     */
    protected function getFormatter()
    {
        return $this->formatter;
    }
}
