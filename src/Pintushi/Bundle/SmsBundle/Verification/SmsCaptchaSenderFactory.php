<?php

namespace Pintushi\Bundle\SmsBundle\Verification;

use Doctrine\Common\Cache\Cache;
use Overtrue\EasySms\EasySms;
use Pintushi\Bundle\SmsBundle\Factory\SmsFactory;

class SmsCaptchaSenderFactory
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(
        Cache $cache,
        $ttl = 30
    ) {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function create(EasySms $sms)
    {
        return new SmsCaptchaSender($sms, $this->cache, $this->ttl);
    }
}
