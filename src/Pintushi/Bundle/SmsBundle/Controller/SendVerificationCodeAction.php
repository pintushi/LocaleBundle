<?php

namespace Pintushi\Bundle\SmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Pintushi\Bundle\SmsBundle\Verification\SmsCaptchaSender;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Overtrue\EasySms\Exceptions\GatewayErrorException;

class SendVerificationCodeAction extends Controller
{
    private $smsCaptchaSender;

    public function __construct(SmsCaptchaSender $smsCaptchaSender)
    {
        $this->smsCaptchaSender = $smsCaptchaSender;
    }

    public function __invoke(Request $request)
    {
        return $this->smsCaptchaSender->send($request->attributes('phone'));
    }
}
