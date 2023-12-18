<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $subject = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Technology $technology = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?User $owner = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Status $status = null;

    #[ORM\Column]
    private ?bool $priority = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?User $angel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTechnology(): ?Technology
    {
        return $this->technology;
    }

    public function setTechnology(?Technology $technology): static
    {
        $this->technology = $technology;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isPriority(): ?bool
    {
        return $this->priority;
    }

    public function setPriority(bool $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getAngel(): ?User
    {
        return $this->angel;
    }

    public function setAngel(?User $angel): static
    {
        $this->angel = $angel;

        return $this;
    }
}
