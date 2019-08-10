<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class JwtSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    private $objectManager;

    /**
     * CurrentUserSubscriber constructor.
     * @var $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $objectManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->objectManager = $objectManager;
    }

    public function onLexikJwtAuthenticationOnJwtCreated($event)
    {
        $payload = $event->getData();
        $user = $this->objectManager->getRepository(User::class)->findOneBy(['username' => $payload['username']]);
        $payload['id'] = $user->getId();
        $payload['image'] = $user->getImage();
        $payload['uniqueToken'] = $user->getToken();

        $event->setData($payload);
    }

    public static function getSubscribedEvents()
    {
        return [
           'lexik_jwt_authentication.on_jwt_created' => 'onLexikJwtAuthenticationOnJwtCreated',
        ];
    }
}
