<?php

namespace App\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsFutureDateValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint) :void
    {
        $todayDate = new \DateTime();
        $dateDiffSign = $todayDate->diff($date)->format('%R%');

        if ($dateDiffSign === "-") {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $dateDiffSign)
                ->addViolation();
        }
    }
}
