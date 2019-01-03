<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusEventRepository")
 */
class StatusEvent
{
    /**
     * List of event's status
     */
    const EVENT_STATUS = [
        [0, 'En préparation', 'secondary'],
        [1, 'Inscription', 'info'],
        [2, 'Complet', 'danger'],
        [3, 'En cours', 'success'],
        [4, 'Terminé', 'light']
    ];

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
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="statusEvent")
     */
    private $events;

    public function __construct()
    {
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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getInPreparationState() : int
    {
        return self::EVENT_STATUS[0][0];
    }

    public function getRegistrationState() : int
    {
        return self::EVENT_STATUS[1][0];
    }

    public function getFullState() : int
    {
        return self::EVENT_STATUS[2][0];
    }

    public function getInProgressState() : int
    {
        return self::EVENT_STATUS[3][0];
    }

    public function getFinishState() : int
    {
        return self::EVENT_STATUS[4][0];
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setStatusEvent($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getStatusEvent() === $this) {
                $event->setStatusEvent(null);
            }
        }

        return $this;
    }
}
