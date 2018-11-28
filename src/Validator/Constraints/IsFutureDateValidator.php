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
            $dateDiffSign = $todayDate // La date actuelle
            ->diff($date) // Compare avec la date entrée par l'utilisateur
            ->format('%R%') // Donne le signe de la comparaison (+ ou -)
            ;

            if ($dateDiffSign === "-") {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $dateDiffSign)
                    ->addViolation();
            }
        }
    }
}
