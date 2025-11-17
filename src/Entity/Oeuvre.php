<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(name: "Id_oeuvre", type: "integer")]
  private int $Id_oeuvre;



  #[ORM\Column]
  private string $image;

  #[ORM\Column(length: 255)]
  private ?string $titre = null;

  #[ORM\Column(length: 255)]
  private ?string $description = null;

  #[ORM\Column]
  private ?float $Prix = null;







    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): static
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): static
    {
        $this->Image = $Image;

        return $this;
    }



    
}
