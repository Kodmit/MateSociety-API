<?php

namespace App\Controller;

use App\Entity\GroupFeed;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GroupFeedLike
{
    private $objectManager;
    private $request;
    private $tokenStorage;

    public function __construct(ObjectManager $objectManager, RequestStack $request, TokenStorageInterface $tokenStorage)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(GroupFeed $data): GroupFeed
    {
        $likes = $data->getLikes();
        $user = $this->tokenStorage->getToken()->getUser();

        if (($key = array_search($user->getId(), $likes)) !== false) {
            unset($likes[$key]);
        }
        else {
            array_push($likes, $user->getId());
        }

        $data->setLikes($likes);
        $this->objectManager->flush();

        return $data;
    }
}