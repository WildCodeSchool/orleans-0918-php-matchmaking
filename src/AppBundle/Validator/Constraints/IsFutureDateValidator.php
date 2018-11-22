<?php

namespace App\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsFutureDateValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        $todayDate = new \DateTime();
        $dateDiffSign = $todayDate->diff($date)->format('%R%');

        if ( $dateDiffSign === "-") {
            // If you're using the new 2.5 validation API (you probably are!)
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $dateDiffSign)
                ->addViolation();
        }
    }
}
