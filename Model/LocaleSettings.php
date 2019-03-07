<?php

namespace Pintushi\Bundle\LocaleBundle\Model;

use Symfony\Component\Intl\Intl;

class LocaleSettings
{
    private $timeZone;
    private $locale;
    private $currency;

    public function __construct($currency = 'CNY')
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        if (null === $this->timeZone) {
            $this->timeZone = date_default_timezone_get();
        }

        return $this->timeZone;
    }

    /**
     * @param mixed $timeZone
     *
     * @return self
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        if (null === $this->locale) {
            $this->locale = \Locale::getDefault();
        }

        return $this->locale;
    }

    /**
     * @param mixed $locale
     *
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     *
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }
}
