<?php

namespace App\Service;

/**
 * Interface CsvImportInterface
 * @package App\Service
 */
interface CsvImportInterface
{
    /**
     * Import CSV
     */
    public function import() : void;
}
