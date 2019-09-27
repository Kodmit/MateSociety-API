<?php
/** Return the connected User */
namespace App\Controller\User;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetCurrentUser
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

    public function __invoke()
    {
        return $this->tokenStorage->getToken()->getUser();
    }
}