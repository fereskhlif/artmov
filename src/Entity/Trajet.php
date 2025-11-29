<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TrajetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message:"le champ date de départ est obligatoire")]
    private ?\DateTimeInterface $date_dep = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message:"le champ date d'arrivée est obligatoire")]
    private ?\DateTimeInterface $date_arr = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ ville de départ est obligatoire")]
    private ?string $ville_dep = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ ville d'arrivée est obligatoire")]
    private ?string $ville_arr = null;

    #[ORM\ManyToOne(inversedBy: 'trajets')]
    #[Assert\NotBlank(message:"le champ vehicule est obligatoire")]
    private ?Vehicule $vehicule = null;

    #[ORM\Column]
    private ?int $nb_places = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = 'Disponible';


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDep(): ?\DateTimeInterface
    {
        return $this->date_dep;
    }

    public function setDateDep(?\DateTimeInterface $date_dep): static
    {
        $this->date_dep = $date_dep;
        return $this;
    }

    public function getDateArr(): ?\DateTimeInterface
    {
        return $this->date_arr;
    }

    public function setDateArr(?\DateTimeInterface $date_arr): static
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

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;
        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nb_places;
    }

    public function setNbPlaces(int $nb_places): static
    {
        $this->nb_places = $nb_places;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }
}
