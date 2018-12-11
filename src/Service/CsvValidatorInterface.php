<?php

namespace App\Service;

/**
 * Interface CsvValidatorInterface
 * @package App\Service
 */
interface CsvValidatorInterface
{
    /**
     * Validate if CSV formating is ok
     * @return bool
     */
    public function validate() : bool;
}
