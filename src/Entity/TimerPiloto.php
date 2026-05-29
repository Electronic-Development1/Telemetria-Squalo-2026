<?php

namespace App\Entity;

use App\Repository\TimerPilotoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimerPilotoRepository::class)]
class TimerPiloto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'no')]
    private ?Piloto $piloto = null;

    #[ORM\Column]
    private ?int $numero_vueltas = null;

    #[ORM\Column(length: 255)]
    private ?string $cronometro = null;

    #[ORM\Column]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column]
    private ?\DateTime $fecha_final = null;

    #[ORM\ManyToOne]
    private ?Evento $evento = null;

    #[ORM\ManyToOne]
    private ?Piloto $piloto_entra = null;

    public function diff()
    {
        $intervalo = $this->fecha_final->diff($this->fecha_inicio);

        $segundosTotales = abs($this->fecha_final->getTimestamp() - $this->fecha_inicio->getTimestamp());

        $minutos = floor($segundosTotales / 60);
        $segundos = $segundosTotales % 60;
        return "$minutos:$segundos sg";
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPiloto(): ?Piloto
    {
        return $this->piloto;
    }

    public function setPiloto(?Piloto $piloto): static
    {
        $this->piloto = $piloto;

        return $this;
    }

    public function getNumeroVueltas(): ?int
    {
        return $this->numero_vueltas;
    }

    public function setNumeroVueltas(int $numero_vueltas): static
    {
        $this->numero_vueltas = $numero_vueltas;

        return $this;
    }

    public function getCronometro(): ?string
    {
        return $this->cronometro;
    }

    public function setCronometro(string $cronometro): static
    {
        $this->cronometro = $cronometro;

        return $this;
    }

    public function getFechaInicio(): ?\DateTime
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(\DateTime $fecha_inicio): static
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFinal(): ?\DateTime
    {
        return $this->fecha_final;
    }

    public function setFechaFinal(\DateTime $fecha_final): static
    {
        $this->fecha_final = $fecha_final;

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): static
    {
        $this->evento = $evento;

        return $this;
    }

    public function getPilotoEntra(): ?Piloto
    {
        return $this->piloto_entra;
    }

    public function setPilotoEntra(?Piloto $piloto_entra): static
    {
        $this->piloto_entra = $piloto_entra;

        return $this;
    }
}
