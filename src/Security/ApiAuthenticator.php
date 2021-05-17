<?php

namespace App\Security;

use App\Entity\Core\CoreUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiAuthenticator extends AbstractGuardAuthenticator
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request)
    {
        return (bool)preg_match('/^\/api\/secure/', $request->getBasePath());
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get("auth");
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ( $credentials == null ) return null;

        $user = $this->entityManager->getRepository(CoreUser::class)->findBy( ['apiKey' => $credentials] );
        if (!$user || $user->getApiKeyExpiry() < new \DateTime())
        {
            throw new CustomUserMessageAuthenticationException("Expired Token");
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'success' => false,
            'message' => $exception->getMessage()
        ], Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
