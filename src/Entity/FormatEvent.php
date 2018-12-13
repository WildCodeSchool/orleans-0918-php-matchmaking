<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormatEventRepository")
 * @UniqueEntity("numberOfPlayers")
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
     * @ORM\Column(type="integer", unique=true)
     */
    private $numberOfPlayers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RoundEvent", mappedBy="formatEvent", cascade={"persist", "remove"})
     */
    private $roundEvents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="formatEvent")
     */
    private $events;


    public function __construct()
    {
        $this->roundEvents = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    /**
     * @return Collection|Event[]
    */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @param RoundEvent $roundEvent
     * @return FormatEvent
     */
    public function addRoundEvent(RoundEvent $roundEvent): self
    {
        if (!$this->roundEvents->contains($roundEvent)) {
            $this->roundEvents[] = $roundEvent;
            $roundEvent->setFormatEvent($this);
        }

        return $this;
    }

    /**
     * @param RoundEvent $roundEvent
     * @return FormatEvent
     */
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

    /**
     * @param Event $event
     * @return FormatEvent
     */
    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setFormatEvent($this);
        }

        return $this;
    }

    /**
     * @param Event $event
     * @return FormatEvent
     */
    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getFormatEvent() === $this) {
                $event->setFormatEvent(null);
            }
        }

        return $this;
    }
}
