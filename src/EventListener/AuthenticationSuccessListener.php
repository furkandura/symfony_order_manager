<?php
namespace App\EventListener;


use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $newResponse = [];
        $newResponse["status"] = "success";
        $newResponse["message"] = "İşlem başarılı";
        $newResponse["data"] = [
            "token" => $data["token"]
        ];


        $event->setData($newResponse);
    }
}
