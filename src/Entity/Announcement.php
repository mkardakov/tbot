<?php

namespace App\Entity;

use App\Repository\AnnouncementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Telegram\Bot\Objects\Message;
use App\DataTransformer\DTO\Request;

/**
 * @ORM\Entity(repositoryClass=AnnouncementRepository::class)
 * @ApiResource(
 *   input=Request::class,
 *   output=Announcement::class
 * )
 */
class Announcement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $action;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $breed;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $animal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $contacts = [];

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="announcement", orphanRemoval=true)
     */
    private $photos;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="announcements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?bool
    {
        return $this->action;
    }

    public function setAction(bool $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(?string $breed): self
    {
        $this->breed = $breed;

        return $this;
    }

    public function getAnimal(): ?string
    {
        return $this->animal;
    }

    public function setAnimal(string $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getContacts(): ?array
    {
        return $this->contacts;
    }

    public function setContacts(?array $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAnnouncement($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAnnouncement() === $this) {
                $photo->setAnnouncement(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
