<?php

namespace App\Security;

use App\Entity\Fund;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;
    private Security $security;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        Security $security
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->security = $security;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $token = $this->fetchAccessToken($client);
        return new SelfValidatingPassport(
            new UserBadge($token->getToken(), function () use ($token, $client) {
                /**
                 * @var GoogleUser $googleUser
                 */
                $googleUser = $client->fetchUserFromToken($token);
                $email = $googleUser->getEmail();

                $user = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()]);
                $existingUserWithEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if(!$user){
                    if($existingUserWithEmail){
                        $existingUserWithEmail->setGoogleId($googleUser->getId());
                        $existingUserWithEmail->setHostedDomain($googleUser->getHostedDomain());
                        $this->entityManager->persist($existingUserWithEmail);
                        $this->entityManager->flush();
                        return $existingUserWithEmail;
                    }
                    $user = new User();
                    $fund = new Fund();
                    $fund->setUser($user);
                    $user
                        ->setGoogleId($googleUser->getId())
                        ->setEmail($email)
                        ->setRoles([User::ROLE_USER])
                        ->setSex(User::MAN)
                        ->setFirstname($googleUser->getFirstName())
                        ->setLastname($googleUser->getLastName())
                        ->setIsVerified(true)
                        ->setHostedDomain($googleUser->getHostedDomain())
                    ;
                    $this->entityManager->persist($fund);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                }

                return $user;
            }),

        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if (in_array('ROLE_ADMIN', $this->security->getUser()->getRoles()))
        {
            return new RedirectResponse($this->router->generate('admin'));
        }
        return new RedirectResponse($this->router->generate('app_dashboard'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
