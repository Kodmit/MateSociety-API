<?php
/*
 * Current User Subscriber
 *
 * This subscriber link the current user object or group to assigned entities fields.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\GroupEvent;
use App\Entity\GroupFeed;
use App\Entity\GroupGoal;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
            /** @var User $user */
            $user = $this->tokenStorage->getToken()->getUser();

            if(method_exists($object, "setCreator")){
                if(!$object->getCreator()){
                    $object->setCreator($this->tokenStorage->getToken()->getUser());

                    // Set the user to the Group if he create it
                    if(get_class($object) == "App\\Entity\\Group"){
                        $user->addGroupsMember($object);
                        $this->manager->flush();
                    }

                    // Set the Group to the GroupEvent if the user create an event and idem for GroupGoal
                    // and check if he's the owner of the Group

                    if(get_class($object) == "App\\Entity\\GroupEvent"){
                        if(!$user->getOwnedGroup())
                            throw new AccessDeniedException("You are not the owner of the group");

                        /** @var GroupEvent $object */
                        $object->setGroup($user->getOwnedGroup());
                        $this->manager->flush();
                    }
                    $event->setControllerResult($object);
                }
            }

            // If method setCreator doesn't exist
            if(get_class($object) == "App\\Entity\\GroupGoal"){
                if(!$user->getOwnedGroup())
                    throw new AccessDeniedException("You are not the owner of the group");

                /** @var GroupGoal $object */
                $object->setGroup($user->getOwnedGroup());
                $this->manager->flush();
            }

            // Set the user group to a GroupFeed post
            if(get_class($object) == "App\\Entity\\GroupFeed"){
                /** @var GroupFeed $object */
                $object->setGroup($user->getGroupMember());
                $this->manager->flush();
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
