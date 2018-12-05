<?php

namespace App\Service;

use App\Exception\CsvException;
use League\Csv\Reader;

abstract class Csv implements CsvImportInterface, CsvValidatorInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * contains CSV data
     * @var array
     */
    private $dataset;

    /**
     * @var bool
     */
    private $state = false;

    /**
     * @var array
     */
    private $csvHeaderFormat = [];

    /**
     * Read CSV file
     */
    public function read(): void
    {
        $csv = Reader::createFromPath($this->getPath());
        $recordsIterator = $csv->getRecords();
        $this->setDataset(iterator_to_array($recordsIterator));
    }

    /**
     * Valid CSV
     * @return bool
     */
    public function validate(): bool
    {
        if (!file_exists($this->getPath())) {
            throw new \LogicException('File does not exist !');
        }

        if (!in_array(mime_content_type($this->getPath()), ['text/csv', 'text/plain'])) {
            throw new \LogicException('File format is not valid !');
        }

        $this->read();
        $result = $this->_validate();
        $this->setState($result);

        return $this->isState();
    }

    /**
     * @return bool
     */
    abstract protected function _validate() : bool;

    /**
     * Import CSV
     */
    public function import(): void
    {
        if (!$this->isState()) {
            throw new \LogicException("Validate method must be call before import method !");
        }

        $this->_import();
    }

    abstract protected function _import() : void;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Csv
     */
    public function setName(string $name): Csv
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Csv
     */
    public function setPath(string $path): Csv
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getDataset(): array
    {
        return $this->dataset;
    }

    /**
     * @param array $dataset
     * @return Csv
     */
    public function setDataset(array $dataset): Csv
    {
        $this->dataset = $dataset;
        return $this;
    }

    /**
     * @return bool
     */
    public function isState(): bool
    {
        return $this->state;
    }

    /**
     * @param bool $state
     * @return Csv
     */
    public function setState(bool $state): Csv
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return array
     */
    public function getCsvHeaderFormat(): array
    {
        return $this->csvHeaderFormat;
    }

    /**
     * @param array $csvHeaderFormat
     * @return Csv
     */
    public function setCsvHeaderFormat(array $csvHeaderFormat): Csv
    {
        $this->csvHeaderFormat = $csvHeaderFormat;
        return $this;
    }
}
