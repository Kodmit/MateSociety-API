<?php
namespace App\Validator\Constraints;

use App\Entity\GroupInfo;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsCreatorValidator extends ConstraintValidator
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!is_object($value))
            throw new UnexpectedValueException($value, 'object');

        /** @var GroupInfo $object */
        /** @var User $user */

        $object = $value;
        $user = $this->tokenStorage->getToken()->getUser();

        if($object !== $user->getOwnedGroup())
            $this->context->buildViolation($constraint->message)->addViolation();

    }
}