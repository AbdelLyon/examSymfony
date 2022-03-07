<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

  #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'likes')]
  private $author;

  #[ORM\ManyToOne(targetEntity: Film::class, inversedBy: 'likes')]
  private $film;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getAuthor(): ?User
  {
    return $this->author;
  }

  public function setAuthor(?User $author): self
  {
    $this->author = $author;

    return $this;
  }

  public function getFilm(): ?Film
  {
    return $this->film;
  }

  public function setFilm(?Film $film): self
  {
    $this->film = $film;

    return $this;
  }
}
