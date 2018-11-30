<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Csv implements CsvImportInterface, CsvValidatorInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var EntityManagerInterface
     */
    private $em;

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
     * Defined a CSV header Format
     */
    const CSV_HEADER_FORMAT = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Verify if CSV file is correctly formating
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

        $this->setState(true);

        return $this->isState();
    }

    /**
     * Test and Import format in database
     */
    public function import(): void
    {
        if (!$this->isState()) {
            throw new \LogicException("Validate method must be call before import method !");
        }
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
     * @return EntityManagerInterface
     */
    public function getEm(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * @param EntityManagerInterface $em
     * @return Csv
     */
    public function setEm(EntityManagerInterface $em): Csv
    {
        $this->em = $em;
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
}
