<?php
// api/src/Serializer/BookAttributeNormalizer.php

namespace App\Serializer;

use App\Entity\Group;
use App\Entity\GroupInfo;
use App\Entity\JoinRequest;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GroupInfoAttributeNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'GROUP_INFO_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        if ($this->userHasPermissionsForGroup($object)) {
            $context['groups'][] = 'is_member:group_info';
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

        return $data instanceof GroupInfo;
    }

    private function userHasPermissionsForGroup($object)
    {
        /** @var GroupInfo $object */
        /** @var User $user */

        $user = $this->tokenStorage->getToken()->getUser();
        if($object->getGroup() === $user->getGroupMember())
            return true;
        return false;
    }
}