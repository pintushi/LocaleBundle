<?php

namespace Pintushi\Bundle\GridBundle\Extension\Formatter\Property;

use Pintushi\Bundle\GridBundle\Datasource\ResultRecordInterface;
use Oro\Bundle\UIBundle\Twig\Environment;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\RouterInterface;

class LinkProperty extends UrlProperty
{
    const TEMPLATE = 'PintushiGridBundle:Extension:Formatter/Property/linkProperty.html.twig';

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @param RouterInterface $router
     * @param Environment     $twig
     */
    public function __construct(RouterInterface $router, Environment $twig)
    {
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawValue(ResultRecordInterface $record)
    {
        $label = null;
        $link = null;

        try {
            $label = $record->getValue($this->getOr(self::DATA_NAME_KEY, $this->get(self::NAME_KEY)));
        } catch (\LogicException $e) {
        }

        try {
            $link = parent::getRawValue($record);
        } catch (InvalidParameterException $e) {
        }

        return $this->twig
            ->loadTemplate(self::TEMPLATE)
            ->render(
                [
                    'url'   => $link,
                    'label' => $label
                ]
            );
    }
}
