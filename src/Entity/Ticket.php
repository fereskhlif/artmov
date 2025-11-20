<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $prixtot = null;


    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;



    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrixtot(): ?int
    {
        return $this->prixtot;
    }

    public function setPrixtot(int $prixtot): static
    {
        $this->prixtot = $prixtot;

        return $this;
    }
}
