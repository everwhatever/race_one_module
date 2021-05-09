<?php

namespace App\League\Domain\Model;

use App\Driver\Domain\Model\Driver;
use App\League\Infrastructure\Repository\LeagueRepository;
use App\Race\Domain\Model\Race;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=LeagueRepository::class)
 */
class League
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=Race::class, mappedBy="league")
     */
    private Collection $Races;

    /**
     * @ORM\ManyToMany(targetEntity=Driver::class, inversedBy="leagues")
     */
    private Collection $Drivers;

    /**
     * @ORM\Column(type="array")
     */
    private array $positions = [];

    #[Pure] public function __construct()
    {
        $this->Races = new ArrayCollection();
        $this->Drivers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRaces(): Collection
    {
        return $this->Races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->Races->contains($race)) {
            $this->Races[] = $race;
            $race->setLeague($this);
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        if ($this->Races->removeElement($race)) {
            // set the owning side to null (unless already changed)
            if ($race->getLeague() === $this) {
                $race->setLeague(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDrivers(): Collection
    {
        return $this->Drivers;
    }

    public function addDriver(Driver $driver): self
    {
        if (!$this->Drivers->contains($driver)) {
            $this->Drivers[] = $driver;
        }

        return $this;
    }

    public function removeDriver(Driver $driver): self
    {
        $this->Drivers->removeElement($driver);

        return $this;
    }

    public function getPositions(): ?array
    {
        return $this->positions;
    }

    public function setPositions(array $positions): self
    {
        $this->positions = $positions;

        return $this;
    }
}
