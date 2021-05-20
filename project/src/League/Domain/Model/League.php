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
     * @ORM\Column(type="array")
     */
    private array $racesIds;

    /**
     * @ORM\Column(type="array")
     */
    private array $driversIds;

    /**
     * @ORM\Column(type="array")
     */
    private array $positions = [];

    #[Pure] public function __construct()
    {
        $this->racesIds = [];
        $this->driversIds = [];
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
     * @return array
     */
    public function getRacesIds(): array
    {
        return $this->racesIds;
    }

    public function addRacesIds(array $racesIds): void
    {
        foreach ($racesIds as $racesId){
            $this->addRaceId($racesId);
        }
    }

    public function addRaceId(int $raceId): self
    {
        if (!in_array($raceId, $this->racesIds)) {
            $this->racesIds[] = $raceId;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDriversIds(): array
    {
        return $this->driversIds;
    }

    public function addDriversIds(array $driversIds): void
    {
        foreach ($driversIds as $driversId){
            $this->addDriverId($driversId);
        }
    }

    public function addDriverId(int $driverId): self
    {
        if (!in_array($driverId, $this->driversIds)) {
            $this->driversIds[] = $driverId;
        }

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
