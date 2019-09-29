<?php
/** Return the most popular groups */
namespace App\Controller\Group;

use App\Entity\Group;
use Doctrine\Common\Persistence\ObjectManager;

class LastGroups
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function __invoke()
    {
        $groups = $this->objectManager->getRepository(Group::class)->findBy([], ['createdAt' => 'ASC'], 5);
        return $groups;
    }
}