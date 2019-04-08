<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class EnableUser
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