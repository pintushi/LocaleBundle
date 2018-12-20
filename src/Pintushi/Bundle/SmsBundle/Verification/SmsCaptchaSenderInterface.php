<?php
namespace Pintushi\Bundle\SmsBundle\Verification;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
interface SmsCaptchaSenderInterface
{
    /**
     * @param $phone
     * @return array
     */
    public function send($phone);
}
