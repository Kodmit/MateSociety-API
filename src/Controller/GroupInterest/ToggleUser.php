<?php

namespace App\Controller\GroupInterest;

use App\Entity\Group;
use App\Entity\GroupFeed;
use App\Entity\GroupInterest;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ToggleUser
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

    public function __invoke(GroupInterest $data)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $groupInterests = $user->getGroupInterests();
        $arr = [];

        foreach ($groupInterests as $groupInterest) {
            array_push($arr, $groupInterest->getId());
        }

        if (array_search($data->getId(), $arr) !== false) {
            $data->removeUser($user);
        } else {
            $data->addUser($user);
        }
        $this->objectManager->flush();
        return $data;
    }
}