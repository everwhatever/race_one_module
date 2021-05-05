<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
    private Collection $drivers;

    /**
     * @ORM\OneToMany(targetEntity=Time::class, mappedBy="races")
     */
    private Collection $times;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=League::class, inversedBy="Races")
     */
    private ?League $league;

    private function __construct(string $name, array $drivers)
    {
        $this->drivers = new ArrayCollection();
        $this->times = new ArrayCollection();

        $this->name = $name;
        $this->addDrivers($drivers);
        $this->date = new DateTime();
    }

    /**
     * @param Driver[] $drivers
     */
    private function addDrivers(array $drivers): void
    {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->drivers->contains($driver)) {
            $this->drivers[] = $driver;
            $driver->addRace($this);
        }

        return $this;
    }

    public static function create(string $name, array $drivers): self
    {
        return new self($name, $drivers);
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
