<?php

namespace App\Entity;

use App\Repository\PilotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PilotoRepository::class)]
class Piloto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $movil = null;

    /**
     * @var Collection<int, TimerPiloto>
     */
    #[ORM\OneToMany(targetEntity: TimerPiloto::class, mappedBy: 'piloto')]
    private Collection $no;

    public function __construct()
    {
        $this->no = new ArrayCollection();
    }

      public function __toString()
    {
        return " {$this->nombre} | Móvil {$this->movil} ";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getMovil(): ?string
    {
        return $this->movil;
    }

    public function setMovil(string $movil): static
    {
        $this->movil = $movil;

        return $this;
    }

    /**
     * @return Collection<int, TimerPiloto>
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(TimerPiloto $no): static
    {
        if (!$this->no->contains($no)) {
            $this->no->add($no);
            $no->setPiloto($this);
        }

        return $this;
    }

    public function removeNo(TimerPiloto $no): static
    {
        if ($this->no->removeElement($no)) {
            // set the owning side to null (unless already changed)
            if ($no->getPiloto() === $this) {
                $no->setPiloto(null);
            }
        }

        return $this;
    }
}
