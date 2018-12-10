<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="datetime")
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     * message = "La valeur ne peut être nulle"
     * )
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $roundMinutes;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     * message = "La valeur ne peut être nulle"
     * )
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
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     * message = "La valeur ne peut être nulle"
     * )
     * @Assert\GreaterThanOrEqual(
     * value = 0,
     * message = "La valeur ne peut être inférieure à 0"
     * )
     */
    private $pauseMinutes;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     * message = "La valeur ne peut être nulle"
     * )
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

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="events")
     */
    private $managers;

    public function __construct()
    {
        $this->managers = new ArrayCollection();
    }

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

    /**
     * @return Collection|User[]
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function setManagers(array $managers): void
    {
        $this->managers = [];
        foreach($managers as $manager) {
            $this->managers[] = $manager;
        }
    }

    public function addManager(User $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
        }

        return $this;
    }

    public function removeManager(User $manager): self
    {
        if ($this->managers->contains($manager)) {
            $this->managers->removeElement($manager);
        }

        return $this;
    }

    public function removeAllManagers(): self
    {
        $this->managers = [];
        return $this;
    }

}
