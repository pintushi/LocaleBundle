<?php

namespace Pintushi\Bundle\SmsBundle\Verification;

use Doctrine\Common\Cache\Cache;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Message;
use Pintushi\Bundle\SmsBundle\Builder\MessageTemplateBuilderInterface;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class SmsCaptchaSender implements SmsCaptchaSenderInterface
{
    const CACHE_KEY = 'phone_number_verification';

    /**
     * @var  ProviderInterface
     */
    protected $sms;

    /**
     * @var Cache
     */
    protected $cache;

    protected $messageTemplateBuilder;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(
        EasySms $sms,
        Cache $cache,
        $ttl = 30
    ) {
        $this->sms = $sms;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * @param $phoneNumber
     * @return array
     */
    public function send($phoneNumber)
    {
        if (!$this->messageTemplateBuilder) {
            throw new \RuntimeException(sprintf('An instance of %s should be set for'.get_class($this), MessageTemplateBuilderInterface::class));
        }

        if (!$this->canSendAgain($phoneNumber)) {
            return array(
                'status' => 'failed',
                'message' => sprintf('发送短信操作太频繁,请%s秒后再试.', $this->ttl),
            );
        }

        $code = $this->generateCode();
        $this->cache->save(self::getCachekey($phoneNumber), [
            'token' => $code,
            'sent_at' => time(),
        ], 300);  //5 min

        $message = new Message([
            'template' => \Closure::bind(function ($gateway) {
                return self::getTemplate($gateway);
            }, $this->messageTemplateBuilder, MessageTemplateBuilderInterface::class),
            'data' => [
                'code' => $code
            ]
        ]);

        return $this->sms->send($phoneNumber, $message);
    }

    public function setMessageTemplateBuilder(MessageTemplateBuilderInterface $messageTemplateBuilder)
    {
        $this->messageTemplateBuilder = $messageTemplateBuilder;
    }

    /**
     * @param $phoneNumber
     * @return string
     */
    public static function getCachekey($phoneNumber)
    {
        return sprintf('%s_%s', self::CACHE_KEY, $phoneNumber);
    }

    /**
     * @param $phoneNumber
     *
     * @return bool
     */
    private function canSendAgain($phoneNumber)
    {
        if ($this->cache->contains(self::getCachekey($phoneNumber))) {
            $data = $this->cache->fetch(self::getCachekey($phoneNumber));
            if (!$data || !isset($data['sent_at'])) {
                return true;
            }

            return time() >= $data['sent_at'] + $this->ttl;
        }

        return true;
    }

    private function generateCode()
    {
        return (string)mt_rand(100000, 999999);
    }
}
