<?php

namespace App\Service;

/**
 * Interface CsvImportInterface
 * @package App\Service
 */
interface CsvImportInterface
{
    /**
     * Import CSV in database
     */
    public function import() : void;
}
