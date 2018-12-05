<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoundEventRepository")
 */
class RoundEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $speechTurn;

    /**
     * @ORM\Column(type="integer")
     */
    private $speaker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormatEvent", inversedBy="roundEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formatEvent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TableEvent", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tableEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeechTurn(): ?int
    {
        return $this->speechTurn;
    }

    public function setSpeechTurn(int $speechTurn): self
    {
        $this->speechTurn = $speechTurn;

        return $this;
    }

    public function getSpeaker(): ?int
    {
        return $this->speaker;
    }

    public function setSpeaker(int $speaker): self
    {
        $this->speaker = $speaker;

        return $this;
    }

    public function getFormatEvent(): ?FormatEvent
    {
        return $this->formatEvent;
    }

    public function setFormatEvent(?FormatEvent $formatEvent): self
    {
        $this->formatEvent = $formatEvent;

        return $this;
    }

    public function getTableEvent(): ?TableEvent
    {
        return $this->tableEvent;
    }

    public function setTableEvent(?TableEvent $tableEvent): self
    {
        $this->tableEvent = $tableEvent;

        return $this;
    }
}
