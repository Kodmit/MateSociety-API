<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

// This controller allow to check if user is in a Group
// If User is in a Group, controller return list of Group IDs
// Else controller return false.
class CheckUserGroup
{
    private $objectManager;
    private $request;

    public function __construct(ObjectManager $objectManager, RequestStack $request)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
    }

    public function __invoke(User $data): User
    {
        $token = $this->request->getCurrentRequest()->attributes->get("token");

        if($token != $data->getToken()){
            throw new \ErrorException("Bad token");
        }
        else{
            $data->setEnabled(true);
            $this->objectManager->flush();
        }

        return $data;
    }
}