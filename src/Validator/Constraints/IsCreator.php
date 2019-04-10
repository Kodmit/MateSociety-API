<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class IsCreator extends Constraint
{
    public $message = 'You are not the owner of this group, nice try.';
}