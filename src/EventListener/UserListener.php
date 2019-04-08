<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserListener implements EventSubscriberInterface
{

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function encodePassword(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $result = $event->getControllerResult();


        switch ($request->getMethod()){
            case("POST"):
                $data = $request->attributes->get("data");
                if(method_exists($data, "getPlainPassword") && $data->getPlainPassword()){
                    $password = $this->userPasswordEncoder->encodePassword($result, $data->getPlainPassword());
                    $result->setPassword($password);
                    $result->eraseCredentials();
                }
                break;
            case("PUT"):
                $data = $request->attributes->get("data");
                if(method_exists($data, "getPlainPassword") && $data->getPlainPassword()){
                    $password = $this->userPasswordEncoder->encodePassword($result, $data->getPlainPassword());
                    if(!$this->userPasswordEncoder->isPasswordValid($result, $data->getPlainPassword())){
                        $result->setPassword($password);
                        $result->eraseCredentials();
                    }
                    else{
                        throw new BadRequestHttpException("Password is the same as origin");
                    }
                }

        }
    }
}