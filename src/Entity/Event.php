<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\IsFutureDate;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Vich\Uploadable
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
     * @ORM\Column(type="text", nullable=true)
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Player", mappedBy="events")
     * @OrderBy({"name" = "ASC", "firstname" = "ASC"})
     */
    private $players;
  
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FormatEvent", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     * message = "La valeur ne peut être nulle"
     * )
     */
    private $formatEvent;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $logo = 'defaultLogo.png';

    /**
     * @Vich\UploadableField(mapping="logos", fileNameProperty="logo")
     * @var File
     * @Assert\Image(maxSize="2M",maxSizeMessage="Cette image est trop volumineuse, 2Mo maximum")
     */
    private $logoFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
  
    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /** @ORM\ManyToOne(targetEntity = "App\Entity\StatusEvent", inversedBy = "events")
     * @ORM\JoinColumn(nullable = false)
     */
    private $statusEvent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Society", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $society;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
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
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->addEvent($this);
        }
      
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

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            $player->removeEvent($this);
        }
  
        return $this;
    }

    public function setLogoFile(File $logo = null)
    {
        $this->logoFile = $logo;

        if ($logo) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getLogoFile()
    {
        return $this->logoFile;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeEvent($this);
        }
    }

    public function getStatusEvent(): ?StatusEvent
    {
        return $this->statusEvent;
    }

    public function setStatusEvent(?StatusEvent $statusEvent): self
    {
        $this->statusEvent = $statusEvent;

        return $this;
    }

    public function getSociety(): ?Society
    {
        return $this->society;
    }

    public function setSociety(?Society $society): self
    {
        $this->society = $society;

        return $this;
    }
}
