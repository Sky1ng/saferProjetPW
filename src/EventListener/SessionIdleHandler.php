<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SessionIdleHandler
{
    protected $tokenStorage;
    protected $session;
    protected $maxIdleTime;

    //Permet de récupérer le temps d'inactivité de la session
    public function __construct(TokenStorageInterface $tokenStorage, $maxIdleTime = 0)
    {
        $this->tokenStorage = $tokenStorage;
        $this->maxIdleTime = $maxIdleTime;
    }

    //Permet de récupérer le temps d'inactivité de la session
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->session = $event->getRequest()->getSession();
        $this->session->start();
        $this->session->set('last_activity', time());
    }

    //Permet de déconnecter l'utilisateur si il est inactif
    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->maxIdleTime > 0) {
            $this->session = $event->getRequest()->getSession();

            if ($this->session->isStarted()) {
                $lapse = time() - $this->session->get('last_activity');
                if ($lapse > $this->maxIdleTime) {
                    $token = $this->tokenStorage->getToken();
                    if (null !== $token) {
                        $this->tokenStorage->setToken(null);
                        $this->session->invalidate();
                    }
                }
            }
        }
    }
}