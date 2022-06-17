<?php

namespace App\Entity;

use App\Repository\PublicationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationsRepository::class)]
class Publications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'publications')]
    private $user;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private $text;

    #[ORM\Column(type: 'string', length: 110, nullable: true)]
    private $document;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'string', length: 40)]
    private $status;

    #[ORM\Column(type: 'datetime')]
    private $created;

    // #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Likes::class)]
    // private $likes;

    #[ORM\Column(type: 'integer', length: 100)]
    private $likes;



    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    // /**
    //  * @return Collection<int, Likes>
    //  */
    // public function getLikes(): Collection
    // {
    //     return $this->likes;
    // }

    // public function addLike(Likes $like): self
    // {
    //     if (!$this->likes->contains($like)) {
    //         $this->likes[] = $like;
    //         $like->setPublication($this);
    //     }

    //     return $this;
    // }

    // public function removeLike(Likes $like): self
    // {
    //     if ($this->likes->removeElement($like)) {
    //         // set the owning side to null (unless already changed)
    //         if ($like->getPublication() === $this) {
    //             $like->setPublication(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * Get the value of likes
     */ 
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set the value of likes
     *
     * @return  self
     */ 
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }
}
