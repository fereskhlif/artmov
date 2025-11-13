<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    /**
     * @var Collection<int, Maintenance>
     */
    #[ORM\OneToMany(targetEntity: Maintenance::class, mappedBy: 'vehicule')]
    private Collection $maintenance;

    public function __construct()
    {
        $this->maintenance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Maintenance>
     */
    public function getMaintenance(): Collection
    {
        return $this->maintenance;
    }

    public function addMaintenance(Maintenance $maintenance): static
    {
        if (!$this->maintenance->contains($maintenance)) {
            $this->maintenance->add($maintenance);
            $maintenance->setVehicule($this);
        }

        return $this;
    }

    public function removeMaintenance(Maintenance $maintenance): static
    {
        if ($this->maintenance->removeElement($maintenance)) {
            // set the owning side to null (unless already changed)
            if ($maintenance->getVehicule() === $this) {
                $maintenance->setVehicule(null);
            }
        }

        return $this;
    }
}
