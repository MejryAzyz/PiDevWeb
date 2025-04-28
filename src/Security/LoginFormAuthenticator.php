<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $utilisateurRepository; // Declare the repository
    private $tokenStorage;
    private $requestStack;


    public function __construct(private UrlGeneratorInterface $urlGenerator,UtilisateurRepository $utilisateurRepository,
    TokenStorageInterface $tokenStorage,
    RequestStack $requestStack
    )

    {        $this->utilisateurRepository = $utilisateurRepository; // Assign it to the property
 $this->tokenStorage = $tokenStorage;
 $this->requestStack = $requestStack;
}

    public function authenticate(Request $request): Passport
    {
        $email = $request->getPayload()->getString('email');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
        $user = $this->utilisateurRepository->findOneByEmail($email);

        // Check if the user is banned (status = 0)
        
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser(); // ✅ Get the user from the token

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        if ($user && $user->getStatus() === 0) {
            $this->tokenStorage->setToken(null);
            $this->requestStack->getSession()->invalidate();

            // Optionally, you can add a flash message or custom error here
        return new RedirectResponse($this->urlGenerator->generate('app_banned')); // Redirect to a banned page

        }
        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_utilisateur_index'));
        
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
