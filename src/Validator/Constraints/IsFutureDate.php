<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsFutureDate extends Constraint
{
    public $message = 'La date est antérieure à la date d\'aujourd\'hui !';
}
