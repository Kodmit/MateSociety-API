<?php
/** Check if the given username exist */
namespace App\Controller\User;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckUsername
{
    private $objectManager;
    private $request;

    public function __construct(ObjectManager $objectManager, RequestStack $request)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
    }

    public function __invoke()
    {
        $username = $this->request->getCurrentRequest()->query->get('username');
        $check = $this->objectManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($check)
            return true;
        return false;
    }
}