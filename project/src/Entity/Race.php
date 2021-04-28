<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=RaceRepository::class)
 */
class Race
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $date;

    /**
     * @ORM\ManyToMany(targetEntity=Driver::class, mappedBy="races")
     */
    private ArrayCollection $drivers;

    /**
     * @ORM\OneToMany(targetEntity=Time::class, mappedBy="races")
     */
    private ArrayCollection $times;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="Races")
     */
    private ?League $league;

    #[Pure] public function __construct()
    {
        $this->drivers = new ArrayCollection();
        $this->times = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDrivers(): Collection
    {
        return $this->drivers;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers[] = $driver;
            $driver->addRace($this);
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        if ($this->drivers->removeElement($driver)) {
            $driver->removeRace($this);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTimes(): Collection
    {
        return $this->times;
    }

    public function addTime(Time $time): self
    {
        if (!$this->times->contains($time)) {
            $this->times[] = $time;
            $time->setRaces($this);
        }

        return $this;
    }

    public function removeTime(Time $time): self
    {
        if ($this->times->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getRaces() === $this) {
                $time->setRaces(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLeague(): ?League
    {
        return $this->league;
    }

    public function setLeague(?League $league): self
    {
        $this->league = $league;

        return $this;
    }
}
