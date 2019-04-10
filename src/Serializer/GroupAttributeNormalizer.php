<?php
// api/src/Serializer/BookAttributeNormalizer.php

namespace App\Serializer;

use App\Entity\Group;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GroupAttributeNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'GROUP_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if ($this->userHasPermissionsForGroup($object)) {
            $context['groups'][] = 'is_admin';
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

        return $data instanceof Group;
    }

    private function userHasPermissionsForGroup($object): bool
    {
        // Get permissions from user in $this->tokenStorage
        // for the current $object (book) and
        // return true or false
        $user = $this->tokenStorage->getToken()->getUser();
        if(array_search("ROLE_ADMIN", $user->getRoles()))
            return true;
        return false;
    }
}