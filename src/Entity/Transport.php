<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date_dep = null;

    #[ORM\Column(length: 255)]
    private ?string $date_arr = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_dep = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_arr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDep(): ?string
    {
        return $this->date_dep;
    }

    public function setDateDep(string $date_dep): static
    {
        $this->date_dep = $date_dep;

        return $this;
    }

    public function getDateArr(): ?string
    {
        return $this->date_arr;
    }

    public function setDateArr(string $date_arr): static
    {
        $this->date_arr = $date_arr;

        return $this;
    }

    public function getVilleDep(): ?string
    {
        return $this->ville_dep;
    }

    public function setVilleDep(string $ville_dep): static
    {
        $this->ville_dep = $ville_dep;

        return $this;
    }

    public function getVilleArr(): ?string
    {
        return $this->ville_arr;
    }

    public function setVilleArr(string $ville_arr): static
    {
        $this->ville_arr = $ville_arr;

        return $this;
    }
}
