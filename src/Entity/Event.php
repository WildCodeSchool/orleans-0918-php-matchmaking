<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\IsFutureDate;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *  min=3,
     *  minMessage = "Le titre doit contenit {{ limit }} caractères minimum"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     * @IsFutureDate
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *  min=3,
     *  minMessage = "Le contenu doit contenit {{ limit }} caractères minimum"
     * )
     */
   
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roundMinutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $roundSeconds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pauseMinutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pauseSeconds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRoundMinutes(): ?int
    {
        return $this->roundMinutes;
    }

    public function setRoundMinutes(?int $roundMinutes): self
    {
        $this->roundMinutes = $roundMinutes;

        return $this;
    }

    public function getRoundSeconds(): ?int
    {
        return $this->roundSeconds;
    }

    public function setRoundSeconds(?int $roundSeconds): self
    {
        $this->roundSeconds = $roundSeconds;

        return $this;
    }

    public function getPauseMinutes(): ?int
    {
        return $this->pauseMinutes;
    }

    public function setPauseMinutes(?int $pauseMinutes): self
    {
        $this->pauseMinutes = $pauseMinutes;

        return $this;
    }

    public function getPauseSeconds(): ?int
    {
        return $this->pauseSeconds;
    }

    public function setPauseSeconds(?int $pauseSeconds): self
    {
        $this->pauseSeconds = $pauseSeconds;

        return $this;
    }
}
