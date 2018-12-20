<?php

namespace Pintushi\Bundle\SmsBundle\Security\Core\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Pintushi\Bundle\SmsBundle\Verification\SmsCaptchaSenderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Pintushi\Bundle\SmsBundle\Security\Core\Authentication\Token\SmsToken;
use Pintushi\Bundle\SmsBundle\Security\Core\Exception\PhoneNumberFormatException;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class SmsAuthProvider implements AuthenticationProviderInterface
{

    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var UserCheckerInterface
     */
    private $userChecker;

    /**
     * @var SmsCaptchaSenderInterface
     */
    private $phoneNumberVerification;

    /**
     * @param UserProviderInterface $userProvider User provider
     * @param UserCheckerInterface $userChecker User checker
     */
    public function __construct(UserProviderInterface $userProvider, SmsCaptchaSenderInterface $phoneNumberVerification, UserCheckerInterface $userChecker)
    {
        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
        $this->phoneNumberVerification = $phoneNumberVerification;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof SmsToken;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(TokenInterface $token)
    {
        /* @var SmsToken $token */
        $user = $this->userProvider->loadUserByUsername($token->getPhoneNumber());

        if (!$user instanceof UserInterface) {
            throw new AuthenticationServiceException('loadUserByUsername() must return a UserInterface.');
        }

        try {
            $this->userChecker->checkPreAuth($user);

            if (!$this->phoneNumberVerification->validate($token->getPhoneNumber(), $token->getVerificationCode())) {
                throw  new  BadCredentialsException();
            }

            $this->userChecker->checkPostAuth($user);
        } catch (BadCredentialsException $e) {
            throw $e;
        }

        $token = new SmsToken($token->getPhoneNumber(), $token->getVerificationCode(), $user->getRoles());
        $token->setUser($user);
        $token->setAuthenticated(true);

        $this->userChecker->checkPostAuth($user);

        return $token;
    }
}
