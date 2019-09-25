<?php
/** Check if the given email exist */
namespace App\Controller\User;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckEmail
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
        $email = $this->request->getCurrentRequest()->query->get('email');
        $check = $this->objectManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($check)
            return true;
        return false;
    }
}