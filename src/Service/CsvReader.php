<?php

namespace App\Service;

use Iterator;
use League\Csv\Reader;

/**
 * Class CsvReader
 * @package App\Service
 */
class CsvReader
{
    /**
     * @param string $path
     * @return Iterator
     * @throws \League\Csv\Exception
     */
    public function read(string $path) : Iterator
    {
        $csvFile = Reader::createFromPath($path, 'r');
        $csvFile->setHeaderOffset(0);
        return $csvFile->getRecords();
    }
}
