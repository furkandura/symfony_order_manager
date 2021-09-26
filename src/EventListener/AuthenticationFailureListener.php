<?php

namespace App\EventListener;

use App\Helper\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;

class AuthenticationFailureListener
{
    /**
     * When this event happened, response can be updated.
     *
     * @param AuthenticationFailureEvent $event the authentication Failure event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $event->setResponse(ResponseHelper::create(400, "Kullanıcı adı veya şifre hatalı"));
    }
}
