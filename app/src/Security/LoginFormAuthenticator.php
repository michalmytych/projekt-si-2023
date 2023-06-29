<?php
/**
 * Login form authenticator.
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

/**
 * Class LoginFormAuthenticator.
 *
 * @class LoginFormAuthenticator
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    /**
     * Login route.
     */
    public const LOGIN_ROUTE = 'app_login';

    /**
     * After login redirect route.
     */
    public const AFTER_LOGIN_REDIRECT_ROUTE = 'article_index';

    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * URL generator interface.
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * CSRF Token Manager.
     */
    private CsrfTokenManagerInterface $csrfTokenManager;

    /**
     * Password encoder.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param UserRepository              $userRepository   User repository
     * @param UrlGeneratorInterface       $urlGenerator     Url generator
     * @param CsrfTokenManagerInterface   $csrfTokenManager CSRF token manager
     * @param UserPasswordHasherInterface $passwordHasher   User password hasher
     */
    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Supports.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * Create a passport for the current request.
     *
     * The passport contains the user, credentials and any additional information
     * that has to be checked by the Symfony Security system. For example, a login
     * form authenticator will probably return a passport containing the user, the
     * presented password and the CSRF token value.
     *
     * You may throw any AuthenticationException in this method in case of error (e.g.
     * a UserNotFoundException when the user cannot be found).
     *
     * @param Request $request HTTP request
     *
     * @return Passport Passport
     *
     * @throws AuthenticationException
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    /**
     * Get credentials.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * Get user.
     *
     * @param mixed $credentials
     *
     * @return User
     */
    public function getUser(mixed $credentials): User
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->userRepository->findOneByEmail($credentials['email']);

        if (!$user) {
            $message = sprintf("User of this email [%s] could not be found.", $credentials['email']);
            throw new NotFoundHttpException($message);
        }

        return $user;
    }

    /**
     * Do on auth success.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param mixed          $firewallName
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, mixed $firewallName): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate(self::AFTER_LOGIN_REDIRECT_ROUTE));
    }

    /**
     * Get login URL.
     *
     * @param Request $request HTTP request
     *
     * @return string Login URL
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
