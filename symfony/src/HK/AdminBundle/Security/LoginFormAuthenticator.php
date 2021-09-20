<?php

namespace HK\AdminBundle\Security;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use HK\CoreBundle\Entity\CmsUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HK\AdminBundle\Router\Router;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use HK\CoreBundle\Entity\CmsUserLoginLog;
use HK\CoreBundle\Entity\CmsIpLock;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;

    private $router;

    private $csrfTokenManager;

    private $passwordEncoder;

    private static $_SESSION_LOGIN_TIMES = 'blogin-times';

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return Router::$LOGIN === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'emailAddress' => $request->get('emailAddress', ''),
            'password' => $request->get('password', ''),
            'csrf_token' => $request->get('_csrf_token', '')
        ];
        $loginTimes = $request->getSession()->get(self::$_SESSION_LOGIN_TIMES . $request->getClientIp()) == null ? 0 : intval($request->getSession()->get(self::$_SESSION_LOGIN_TIMES . $request->getClientIp()));
        if ($loginTimes >= intval(getenv('ADMIN_LOGIN_TIMES')) || $this->entityManager->getRepository(CmsIpLock::class)->isExisting(-1, [
            'ipLocked' => $request->getClientIp()
        ])) {
            $credentials['emailAddress'] = '';
            $credentials['password'] = '';
        }
        $request->getSession()->set(Security::LAST_USERNAME, $credentials['emailAddress']);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(CmsUser::class)->loadUserByUsername($credentials['emailAddress']);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Email address/Phone number could not be found.');
        }

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $this->entityManager->getRepository(CmsUserLoginLog::class)->log($request->get('emailAddress', ''), $request->getClientIp(), false);
        $loginTimes = $request->getSession()->get(self::$_SESSION_LOGIN_TIMES . $request->getClientIp()) == null ? 0 : intval($request->getSession()->get(self::$_SESSION_LOGIN_TIMES . $request->getClientIp()));
        $request->getSession()->set(self::$_SESSION_LOGIN_TIMES . $request->getClientIp(), $loginTimes + 1);
        return parent::onAuthenticationFailure($request, $exception);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        $user = $token->getUser();
        $user->setLastLoggedInAt(new \DateTime(null, new \DateTimeZone('Asia/Ho_Chi_Minh')));
        $user->setLastLoggedInIp($request->getClientIp());
        $this->entityManager->getRepository(CmsUser::class)->saveEntity($user);

        $this->entityManager->getRepository(CmsUserLoginLog::class)->log($request->get('emailAddress', ''), $request->getClientIp(), true);

        $request->getSession()->set(self::$_SESSION_LOGIN_TIMES . $request->getClientIp(), 0);
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse('/admin/dashboard');
    }

    protected function getLoginUrl()
    {
        return $this->router->generate(Router::$LOGIN);
    }
}
