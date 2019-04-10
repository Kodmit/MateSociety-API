<?php
// api/src/Serializer/BookAttributeNormalizer.php

namespace App\Serializer;

use App\Entity\Group;
use App\Entity\JoinRequest;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JoinRequestCreatorAttributeNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'JOIN_REQUEST_CREATOR_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if ($this->userHasPermissionsForGroup($object)) {
            $context['groups'][] = 'is_creator:join_request';
        }

        $context[self::ALREADY_CALLED] = true;

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof JoinRequest;
    }

    private function userHasPermissionsForGroup($object)
    {
        /** @var JoinRequest $object */

        $user = $this->tokenStorage->getToken()->getUser();
        if($object->getCreator() == $user || $object->getGroup()->getCreator() == $user)
            return true;
        return false;
    }
}