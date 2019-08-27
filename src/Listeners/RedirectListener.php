<?php

namespace App\Listeners;

use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RedirectListener
{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $t, RouterInterface $r)
    {
        $this->router = $r;
        $this->tokenStorage = $t;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->isLoggedIn() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('sorteo'));
                $event->setResponse($response);
            }
        }
        if (false === $this->isLoggedIn() && $event->isMasterRequest()) {
            $currentRoute = $event->getRequest()->attributes->get('_route');
            if ($this->isAnonymousUserOnBadPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('register'));
                $event->setResponse($response);
            }
        }
    }

    private function isLoggedIn()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return false;
        }
        $user = $token->getUser();

        return $user instanceof Usuario;
    }

    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return \in_array(
            $currentRoute,
            ['register', 'app_login'],
            true
        );
    }

    private function isAnonymousUserOnBadPage($currentRoute)
    {
        return \in_array(
            $currentRoute,
            ['encuesta', 'borrar', 'comentario'],
            true
        );
    }
}