<?php

namespace Pintushi\Bundle\SmsBundle\Verification;

use Doctrine\Common\Cache\Cache;

class SmsCaptchaValidator
{
     /**
     * @var Cache
     */
    protected $cache;

    public function __construct(
        Cache $cache
    ) {
        $this->cache = $cache;
    }

     /**
     * @param string $phoneNumber
     * @param string $code
     *
     * @return bool
     */
    public function validate($phoneNumber, $code)
    {
        $data = $this->cache->fetch(SmsCaptchaSender::getCachekey($phoneNumber));
        if (!$data || !isset($data['token'])) {
            return false;
        }

        $status = $this->compare($code, $data['token']);
        if ($status) {
            $this->cache->delete(self::getCachekey($phoneNumber));
        }

        return $status;
    }

    /**
     * Run a match comparison on the provided code and the expected code.
     *
     * @param $code
     * @param $expectedCode
     *
     * @return bool
     */
    protected function compare($code, $expectedCode)
    {
        return $expectedCode !== null && is_string($expectedCode) && $code == $expectedCode;
    }
}
