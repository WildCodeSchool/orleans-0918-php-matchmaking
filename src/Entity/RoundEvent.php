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
    private $speechRound;

    /**
     * @ORM\Column(type="integer")
     */
    private $userSpeech;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormatEvent", inversedBy="roundEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formatEvent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TableEvent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tableEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeechRound(): ?int
    {
        return $this->speechRound;
    }

    public function setSpeechRound(int $speechRound): self
    {
        $this->speechRound = $speechRound;

        return $this;
    }

    public function getUserSpeech(): ?int
    {
        return $this->userSpeech;
    }

    public function setUserSpeech(int $userSpeech): self
    {
        $this->userSpeech = $userSpeech;

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
