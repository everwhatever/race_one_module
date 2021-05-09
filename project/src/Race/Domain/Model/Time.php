<?php

namespace App\Race\Domain\Model;

use App\Driver\Domain\Model\Driver;
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
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $time;

    /**
     * @ORM\ManyToOne(targetEntity=Driver::class, inversedBy="times")
     */
    private ?Driver $drivers;

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

    public function getDrivers(): ?Driver
    {
        return $this->drivers;
    }

    public function setDrivers(?Driver $drivers): self
    {
        $this->drivers = $drivers;

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
