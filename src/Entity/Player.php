<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    const DEFAULT_PRESENCE=false;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = "Ce nom n'est pas valide"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * message = "Ce prénom n'est pas valide"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", nullable=true, length=10)
     * @Assert\Length(
     * max=10,
     * maxMessage = "Le numéro de téléphone n'est pas valide"
     * )
     * @Assert\Regex("/0[0-9]{9}/")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email(
     * message = "L'e-mail que vous avez fourni n'est pas une adresse valide.",
     * )
     */
    private $mail;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Event", inversedBy="players")
     */
    private $events;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPresence=self::DEFAULT_PRESENCE;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $speakerNumber = 0;

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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
        }

        return $this;
    }

    public function getIsPresence(): ?bool
    {
        return $this->isPresence;
    }

    public function setIsPresence(?bool $isPresence): self
    {
        $this->isPresence = $isPresence;

        return $this;
    }

    public function getSpeakerNumber(): ?int
    {
        return $this->speakerNumber;
    }

    public function setSpeakerNumber(int $speakerNumber): self
    {
        $this->speakerNumber = $speakerNumber;

        return $this;
    }
}
