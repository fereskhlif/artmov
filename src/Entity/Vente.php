<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_vente = null;


    #[ORM\Column]
    private ?\DateTime $Date_vente = null;

    #[ORM\Column]
    private ?float $montant = null;



    public function getIdVente(): ?int
    {
        return $this->Id_vente;
    }

    public function getDateVente(): ?\DateTime
    {
        return $this->Date_vente;
    }

    public function setDateVente(\DateTime $Date_vente): static
    {
        $this->Date_vente = $Date_vente;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }
}
