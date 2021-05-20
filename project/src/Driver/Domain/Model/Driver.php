<?php

namespace App\Driver\Domain\Model;


use App\Driver\Infrastructure\Repository\DriverRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @method string getUserIdentifier()
 */
class Driver implements PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="array")
     */
    private array $racesIds;

    /**
     * @ORM\Column(type="array")
     */
    private array $timesIds;

    /**
     * @ORM\Column(type="array")
     */
    private array $leaguesIds;

    #[Pure] public function __construct()
    {
        $this->racesIds = [];
        $this->timesIds = [];
        $this->leaguesIds = [];
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }


    public function getRacesIds(): array
    {
        return $this->racesIds;
    }

    public function addRaceId(int $raceId): self
    {
        if (!in_array($raceId, $this->racesIds)) {
            $this->racesIds[] = $raceId;
        }

        return $this;
    }

    public function addRacesIds(array $racesIds): void
    {
        foreach ($racesIds as $racesId){
            $this->addRaceId($racesId);
        }
    }

    /**
     * @return array
     */
    public function getTimesIds(): array
    {
        return $this->timesIds;
    }

    public function addTimeId(int $timeId): self
    {
        if (!in_array($timeId, $this->timesIds)) {
            $this->timesIds[] = $timeId;
        }

        return $this;
    }


    public function getLeaguesIds(): array
    {
        return $this->leaguesIds;
    }

    public function addLeague(int $leagueId): self
    {
        if (!in_array($leagueId, $this->leaguesIds)) {
            $this->leaguesIds[] = $leagueId;
        }

        return $this;
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}
