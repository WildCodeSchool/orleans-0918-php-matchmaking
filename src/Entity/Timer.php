<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\TimerRepository")
 */
class Timer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $roundMinutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\LessThan(
     * value = 60,
     * message = "La valeur ne peut être supérieure à 59"
     * )
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $roundSeconds;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $pauseMinutes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\LessThan(
     * value = 60,
     * message = "La valeur ne peut être supérieure à 59"
     * )
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $pauseSeconds;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoundMinutes(): ?int
    {
        return $this->roundMinutes;
    }

    public function setRoundMinutes(int $roundMinutes): self
    {
        $this->roundMinutes = $roundMinutes;

        return $this;
    }

    public function getRoundSeconds(): ?int
    {
        return $this->roundSeconds;
    }

    public function setRoundSeconds(int $roundSeconds): self
    {
        $this->roundSeconds = $roundSeconds;

        return $this;
    }

    public function getPauseMinutes(): ?int
    {
        return $this->pauseMinutes;
    }

    public function setPauseMinutes(int $pauseMinutes): self
    {
        $this->pauseMinutes = $pauseMinutes;

        return $this;
    }

    public function getPauseSeconds(): ?int
    {
        return $this->pauseSeconds;
    }

    public function setPauseSeconds(int $pauseSeconds): self
    {
        $this->pauseSeconds = $pauseSeconds;

        return $this;
    }
}
