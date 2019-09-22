<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\GroupFeed;
use App\Entity\GroupInterest;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PublicFeed
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

    private static function cmpDate($a, $b) {
        $date1 = date_timestamp_get($a->created_at);
        $date2 = date_timestamp_get($b->created_at);
        return $date1 - $date2;
    }

    public function __invoke()
    {
        $goals = $this->request->getCurrentRequest()->query->get('goals');
        $goals = explode(',', $goals);

        $data= [];

        foreach ($goals as $goal) {
            /** @var GroupInterest $groupInterests */
            $groupInterests = $this->objectManager->getRepository(GroupInterest::class)->find($goal);
            $groups = $groupInterests->getMsGroup();

            foreach ($groups as $group) {
                $groupFeeds = $group->getGroupFeeds();
                if (count($groupFeeds) > 0) {
                    foreach ($groupFeeds as $groupFeed) {
                        if ($groupFeed->getPublic())
                            array_push($data, $groupFeed);
                    }
                }

            }
        }

        usort($data, [$this, 'cmpDate']);
        return $data;
    }
}