<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class UserEnabledSubscriber implements EventSubscriberInterface
{

    private $security;
    private $objectManager;

    public function __construct(Security $security, ObjectManager $objectManager)
    {
        $this->security = $security;
        $this->objectManager = $objectManager;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if($this->security->getUser()){
            if(!$this->security->getUser()->getEnabled()){
                $response = new Response(json_encode(["error" => "user_not_enabled"]));
                $event->setResponse($response);
            }
        }
        else{
            if($event->getRequest()->getMethod() == "POST"){
                if($event->getRequest()->getRequestUri() === "/api/login_check"){
                    $data = $event->getRequest()->getContent();
                    $json = json_decode($data);
                    $username = $json->username;

                    /** @var User $user */
                    if($user = $this->objectManager->getRepository(User::class)->findOneBy(["username" => $username])){
                        if(!$user->getEnabled()){
                            $response = new Response(json_encode(["error" => "user_not_enabled"]));
                            $event->setResponse($response);
                        }
                    }
                }
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 8]
            ]
        ];
    }
}
