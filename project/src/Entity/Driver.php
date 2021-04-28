<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=DriverRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @method string getUserIdentifier()
 */
class Driver implements UserInterface
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
     * @ORM\ManyToMany(targetEntity=Race::class, inversedBy="drivers")
     */
    private ArrayCollection $races;

    /**
     * @ORM\OneToMany(targetEntity=Time::class, mappedBy="drivers")
     */
    private ArrayCollection $times;

    /**
     * @ORM\ManyToMany(targetEntity=League::class, mappedBy="Drivers")
     */
    private ArrayCollection $leagues;

    #[Pure] public function __construct()
    {
        $this->races = new ArrayCollection();
        $this->times = new ArrayCollection();
        $this->leagues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->races->contains($race)) {
            $this->races[] = $race;
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        $this->races->removeElement($race);

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
            $time->setDrivers($this);
        }

        return $this;
    }

    public function removeTime(Time $time): self
    {
        if ($this->times->removeElement($time)) {
            // set the owning side to null (unless already changed)
            if ($time->getDrivers() === $this) {
                $time->setDrivers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLeagues(): Collection
    {
        return $this->leagues;
    }

    public function addLeague(League $league): self
    {
        if (!$this->leagues->contains($league)) {
            $this->leagues[] = $league;
            $league->addDriver($this);
        }

        return $this;
    }

    public function removeLeague(League $league): self
    {
        if ($this->leagues->removeElement($league)) {
            $league->removeDriver($this);
        }

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
