<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikesRepository::class)]
class Likes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'likes')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Publications::class, inversedBy: 'likes')]
    private $publication;

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

    public function getPublication(): ?publications
    {
        return $this->publication;
    }

    public function setPublication(?publications $publication): self
    {
        $this->publication = $publication;

        return $this;
    }
}
