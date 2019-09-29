<?php
/** Return the nearest groups by department */
namespace App\Controller\Group;

use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetNearestGroups
{
    private $objectManager;
    private $tokenStorage;

    public function __construct(ObjectManager $objectManager, TokenStorageInterface $tokenStorage)
    {
        $this->objectManager = $objectManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $groups = $this->objectManager->getRepository(Group::class)->findBy(
            ['department' => $user->getDepartment()],
            null,
            5
        );
        return $groups;
    }
}