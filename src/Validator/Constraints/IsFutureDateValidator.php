<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsFutureDateValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint): void
    {

        if ($date instanceof \DateTime) {
            $todayDate = new \DateTime();

            if ($date < $todayDate) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', "error: past datetime")
                    ->addViolation();
            }
        }
    }
}
