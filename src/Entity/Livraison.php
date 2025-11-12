<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $point_arrive = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointArrive(): ?string
    {
        return $this->point_arrive;
    }

    public function setPointArrive(string $point_arrive): static
    {
        $this->point_arrive = $point_arrive;

        return $this;
    }
}
