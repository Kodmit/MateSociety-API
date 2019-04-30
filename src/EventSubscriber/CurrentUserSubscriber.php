<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    private $manager;

    /**
     * CurrentUserSubscriber constructor.
     * @var $tokenStorage
     * @var $manager
     */
    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $manager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
    }

    public function setCurrentUser(GetResponseForControllerResultEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $object = $event->getControllerResult();

        if($method == "POST"){
            if(method_exists($object, "setCreator")){
                if(!$object->getCreator()){
                    $object->setCreator($this->tokenStorage->getToken()->getUser());
                    // Set the user to the Group
                    if(get_class($object) == "App\\Entity\\Group"){
                        /** @var User $user */
                        $user = $this->tokenStorage->getToken()->getUser();
                        $user->setGroupMember($object);

                        $this->manager->flush();
                    }
                    $event->setControllerResult($object);
                }
            }
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['setCurrentUser', EventPriorities::PRE_WRITE]],
        ];
    }
}
