<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\Service\DriverCreatorStrategy;

use App\Driver\Domain\Model\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class NormalDriverCreatorStrategy implements DriverCreatorStrategyInterface
{
    private EntityManagerInterface $entityManager;
    
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createDriver(string $email, string $password): Driver
    {
        $driver = new Driver();
        $driver->setEmail($email);
        $driver->setPassword($this->passwordEncoder->hashPassword($driver, $password));
        $driver->setRoles(['ROLE_USER']);


        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }
}
