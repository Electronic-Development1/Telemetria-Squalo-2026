<?php

namespace App\Entity;

use App\Repository\TimerCambioRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimerCambioRepository::class)]
#[ORM\Table(name: 'timer_cambio')]
class TimerCambio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Piloto que SALE del vehículo */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Piloto $piloto_sale = null;

    /** Piloto que ENTRA al vehículo */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Piloto $piloto_entra = null;

    #[ORM\Column]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column]
    private ?\DateTime $fecha_final = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tiempo = null;

    #[ORM\ManyToOne]
    private ?Evento $evento = null;

    public function getId(): ?int { return $this->id; }

    public function getPilotoSale(): ?Piloto { return $this->piloto_sale; }
    public function setPilotoSale(?Piloto $piloto): static { $this->piloto_sale = $piloto; return $this; }

    public function getPilotoEntra(): ?Piloto { return $this->piloto_entra; }
    public function setPilotoEntra(?Piloto $piloto): static { $this->piloto_entra = $piloto; return $this; }

    public function getFechaInicio(): ?\DateTime { return $this->fecha_inicio; }
    public function setFechaInicio(\DateTime $fecha_inicio): static { $this->fecha_inicio = $fecha_inicio; return $this; }

    public function getFechaFinal(): ?\DateTime { return $this->fecha_final; }
    public function setFechaFinal(\DateTime $fecha_final): static { $this->fecha_final = $fecha_final; return $this; }

    public function getTiempo(): ?string { return $this->tiempo; }
    public function setTiempo(?string $tiempo): static { $this->tiempo = $tiempo; return $this; }

    public function getEvento(): ?Evento { return $this->evento; }
    public function setEvento(?Evento $evento): static { $this->evento = $evento; return $this; }

    public function diff(): string
    {
        if (!$this->fecha_inicio || !$this->fecha_final) return '0:0 sg';
        $segundosTotales = abs($this->fecha_final->getTimestamp() - $this->fecha_inicio->getTimestamp());
        $minutos = floor($segundosTotales / 60);
        $segundos = $segundosTotales % 60;
        return "$minutos:$segundos sg";
    }
}
