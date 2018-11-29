<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormatEventRepository")
 */
class FormatEvent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfPlayers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoundEvent", mappedBy="formatEvent")
     */
    private $roundEvents;

    public function __construct()
    {
        $this->roundEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumberOfPlayers(): ?int
    {
        return $this->numberOfPlayers;
    }

    public function setNumberOfPlayers(int $numberOfPlayers): self
    {
        $this->numberOfPlayers = $numberOfPlayers;

        return $this;
    }

    /**
     * @return Collection|RoundEvent[]
     */
    public function getRoundEvents(): Collection
    {
        return $this->roundEvents;
    }

    public function addRoundEvent(RoundEvent $roundEvent): self
    {
        if (!$this->roundEvents->contains($roundEvent)) {
            $this->roundEvents[] = $roundEvent;
            $roundEvent->setFormatEvent($this);
        }

        return $this;
    }

    public function removeRoundEvent(RoundEvent $roundEvent): self
    {
        if ($this->roundEvents->contains($roundEvent)) {
            $this->roundEvents->removeElement($roundEvent);
            // set the owning side to null (unless already changed)
            if ($roundEvent->getFormatEvent() === $this) {
                $roundEvent->setFormatEvent(null);
            }
        }

        return $this;
    }
}
