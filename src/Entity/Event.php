<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\AppBundle\Validator\Constraints\IsFutureDate;

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
    private $round_minutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $round_seconds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pause_minutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pause_seconds;

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
        return $this->round_minutes;
    }

    public function setRoundMinutes(?int $round_minutes): self
    {
        $this->round_minutes = $round_minutes;

        return $this;
    }

    public function getRoundSeconds(): ?int
    {
        return $this->round_seconds;
    }

    public function setRoundSeconds(?int $round_seconds): self
    {
        $this->round_seconds = $round_seconds;

        return $this;
    }

    public function getPauseMinutes(): ?int
    {
        return $this->pause_minutes;
    }

    public function setPauseMinutes(?int $pause_minutes): self
    {
        $this->pause_minutes = $pause_minutes;

        return $this;
    }

    public function getPauseSeconds(): ?int
    {
        return $this->pause_seconds;
    }

    public function setPauseSeconds(?int $pause_seconds): self
    {
        $this->pause_seconds = $pause_seconds;

        return $this;
    }
}
