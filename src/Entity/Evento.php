<?php

namespace App\Entity;

use App\Repository\EventoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventoRepository::class)]
class Evento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tipo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha_realizacion = null;

    /**
     * @var Collection<int, Piloto>
     */
    #[ORM\ManyToMany(targetEntity: Piloto::class)]
    private Collection $pilotos;

    public function __construct()
    {
        $this->pilotos = new ArrayCollection();
    }
    public function __toString()
    {
        return " {$this->id} ";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipo(): ?int
    {
        return $this->tipo;
    }

    public function setTipo(int $tipo): static
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getFechaRealizacion(): ?\DateTime
    {
        return $this->fecha_realizacion;
    }

    public function setFechaRealizacion(\DateTime $fecha_realizacion): static
    {
        $this->fecha_realizacion = $fecha_realizacion;

        return $this;
    }

    /**
     * @return Collection<int, Piloto>
     */
    public function getPilotos(): Collection
    {
        return $this->pilotos;
    }

    public function addPiloto(Piloto $piloto): static
    {
        if (!$this->pilotos->contains($piloto)) {
            $this->pilotos->add($piloto);
        }

        return $this;
    }

    public function removePiloto(Piloto $piloto): static
    {
        $this->pilotos->removeElement($piloto);

        return $this;
    }
}
