<?php
/** Return the most popular groups */
namespace App\Controller\Group;

use App\Entity\Group;
use Doctrine\Common\Persistence\ObjectManager;

class MostPopularGroups
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function __invoke()
    {
        $groups = $this->objectManager->getRepository(Group::class)->sortByUsers();
        return $groups;
    }
}