<?php


namespace App\Services\DriverCreatorStrategy;


use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;

class NormalDriverCreatorStrategy implements DriverCreatorStrategyInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createDriver(Driver $driver): void
    {
        $normalDriver = $driver->setRoles(['ROLE_USER']);

        $this->entityManager->persist($normalDriver);
        $this->entityManager->flush();
    }
}