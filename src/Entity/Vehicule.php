<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message:"le champ matricule est obligatoire")]
    #[Assert\Regex(
        pattern: "/^[0-9]{3,4}TUN[0-9]{3,5}$/",
        message: "Le format du matricule est invalide."
    )]



    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: "Le matricule doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le matricule ne doit pas dépasser {{ limit }} caractères."
    )]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ type est obligatoire")]
    #[Assert\Type(
        type: "string",
        message: "Le matricule doit être une chaîne de caractères."
    )]

    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"le champ capacité est obligatoire")]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ état de véhicule est obligatoire")]
    private ?string $etat = null;

    /**
     * @var Collection<int, Trajet>
     */
    #[ORM\OneToMany(targetEntity: Trajet::class, mappedBy: 'vehicule', cascade: ["remove"], orphanRemoval: true)]
    private Collection $trajets;

//    #[ORM\Column]
//    private ?int $nb_places = null;



    public function __construct()
    {
        $this->trajets = new ArrayCollection();

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
     * @return Collection<int, Trajet>
     */
    public function getTrajets(): Collection
    {
        return $this->trajets;
    }

    public function addTrajet(Trajet $trajet): static
    {
        if (!$this->trajets->contains($trajet)) {
            $this->trajets->add($trajet);
            $trajet->setVehicule($this);
        }
        return $this;
    }

    public function removeTrajet(Trajet $trajet): static
    {
        if ($this->trajets->removeElement($trajet)) {
            if ($trajet->getVehicule() === $this) {
                $trajet->setVehicule(null);
            }
        }

        return $this;
    }

//    public function getNbPlaces(): ?int
//    {
//        return $this->nb_places;
//    }

//    public function setNbPlaces(int $nb_places): static
//    {
//        $this->nb_places = $nb_places;
//
//        return $this;
//    }
}
