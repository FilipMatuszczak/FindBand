<?php

namespace App\Security;

use App\Entity\User;
use App\Services\Handler\PasswordHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    const LOGIN_ATTEMPTS_FAILED_THRESHOLD = 3;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $userProvider;
    private $passwordHandler;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider,
        PasswordHandler $passwordHandler
    )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userProvider = $userProvider;
        $this->passwordHandler = $passwordHandler;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['password'];
        $salt = $user->getSalt();
        $userEntity = $this->userProvider->loadUserByUsername($user->getUsername());

        /*if ($userEntity->getOptions() & User::USER_BANNED)
        {
            throw new UnauthorizedHttpException('', 'You were banned due to your behaviour');
        }*/

        /*
        if ($userEntity->getOptions() & User::USER_CHANGING_PASSWORD)
        {
            $userEntity->setOptions($userEntity->getOptions() ^ User::USER_CHANGING_PASSWORD);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }*/
        $this->handleUserLock($userEntity);

        if (!$userEntity->getOptions() & User::USER_VERIFIED)
        {
            return false;
        }

        if ($user->getPassword() === $this->passwordHandler->getHashFromPlainTextAndSalt($password, $salt))
        {
            $userEntity->setLoginAttemptsFailed(0);

            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();

            return true;
        }

        $this->updateAttemptsAndLastLoginFail($userEntity);

        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('mainIndex'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('loginIndex');
    }

    private function handleUserLock(User $user)
    {
        $attempts = $user->getLoginAttemptsFailed();
        $numberOfSeconds = pow(2, $attempts - 3);
        $currentDate = new \DateTime();

        if ($attempts > self::LOGIN_ATTEMPTS_FAILED_THRESHOLD ) {
            $interval = new \DateInterval('PT' . $numberOfSeconds . 'S');
            $userLockDate = $user->getLastLoginFailedDate()->add($interval);

            if ($userLockDate > $currentDate) {
                throw new CustomUserMessageAuthenticationException('Możliwość logowania zablokonawa na jakiś czas, spróbuj później');
            }
        }
    }

    /**
     * @param User $userEntity
     * @throws \Exception
     */
    private function updateAttemptsAndLastLoginFail(User $userEntity): void
    {
        $userEntity->setLastLoginFailedDate(new \DateTime());
        $userEntity->setLoginAttemptsFailed($userEntity->getLoginAttemptsFailed() + 1);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();
    }
}
