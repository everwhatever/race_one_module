<?php

namespace App\Race\Domain\Model;

use App\Race\Infrastructure\Repository\TimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimeRepository::class)
 */
class Time
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $time;

    /**
     * @ORM\Column(type="integer")
     */
    private int $driverId;

    /**
     * @ORM\ManyToOne(targetEntity=Race::class, inversedBy="times")
     */
    private ?Race $races;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDriverId(): int
    {
        return $this->driverId;
    }

    public function setDriverId(int $driverId): self
    {
        $this->driverId = $driverId;

        return $this;
    }

    public function getRaces(): ?Race
    {
        return $this->races;
    }

    public function setRaces(?Race $races): self
    {
        $this->races = $races;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
