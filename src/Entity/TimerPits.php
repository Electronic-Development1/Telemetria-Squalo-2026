<?php

namespace App\Entity;

use App\Repository\TimerPitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimerPitsRepository::class)]
#[ORM\Table(name: 'timer_pits')]
class TimerPits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Piloto $piloto = null;

    #[ORM\Column]
    private ?int $numero_vuelta = null;

    #[ORM\Column]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column]
    private ?\DateTime $fecha_final = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $tiempo = null;

    #[ORM\ManyToOne]
    private ?Evento $evento = null;

    public function getId(): ?int { return $this->id; }

    public function getPiloto(): ?Piloto { return $this->piloto; }
    public function setPiloto(?Piloto $piloto): static { $this->piloto = $piloto; return $this; }

    public function getNumeroVuelta(): ?int { return $this->numero_vuelta; }
    public function setNumeroVuelta(int $numero_vuelta): static { $this->numero_vuelta = $numero_vuelta; return $this; }

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
