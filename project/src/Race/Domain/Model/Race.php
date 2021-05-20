<?php

namespace App\Race\Domain\Model;

use App\Race\Infrastructure\Repository\RaceRepository;
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
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $date;

    /**
     * @ORM\Column(type="array")
     */
    private array $driversIds = [];

    /**
     * @ORM\OneToMany(targetEntity=Time::class, mappedBy="races")
     */
    private Collection $times;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $leagueId;

    private function __construct(string $name, array $driversIds)
    {
        $this->times = new ArrayCollection();

        $this->name = $name;
        $this->addDriversIds($driversIds);
        $this->date = new DateTime();
    }

    /**
     * @param array $driversIds
     */
    private function addDriversIds(array $driversIds): void
    {
        foreach ($driversIds as $driverId) {
            $this->addDriverId($driverId);
        }
    }

    public function addDriverId(int $driverId): self
    {
        if (!in_array($driverId, $this->driversIds)) {
            $this->driversIds[] = $driverId;
        }

        return $this;
    }

    public static function create(string $name, array $driversIds): self
    {
        return new self($name, $driversIds);
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
     * @return array
     */
    public function getDriversIds(): array
    {
        return $this->driversIds;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLeagueId(): ?int
    {
        return $this->leagueId;
    }

    public function setLeagueId(int $leagueId): self
    {
        $this->leagueId = $leagueId;

        return $this;
    }
}
