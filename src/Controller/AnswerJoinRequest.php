<?php

namespace App\Controller;

use App\Entity\JoinRequest;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AnswerJoinRequest
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

    public function __invoke(JoinRequest $data): JoinRequest
    {
        $response = $this->request->getCurrentRequest()->getContent();
        $json = json_decode($response);

        if($this->tokenStorage->getToken()->getUser() !== $data->getGroup()->getCreator())
            throw new AccessDeniedHttpException("You are not the owner of the group.");

        if($data->getStatus() != "awaiting")
            throw new NotAcceptableHttpException("Join request already answered.");

        if($json->response == "accept"){
            $data->setStatus("accepted");
            $user = $data->getCreator();
            $user->setGroupMember($data->getGroup());
        }
        else{
            $data->setStatus("refused");
        }

        if($json->message)
            $data->setResponse($json->message);

        $this->objectManager->flush();
        return $data;
    }
}