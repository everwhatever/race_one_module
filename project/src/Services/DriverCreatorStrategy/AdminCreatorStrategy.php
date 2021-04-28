<?php


namespace App\Services\DriverCreatorStrategy;


use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;

class AdminCreatorStrategy implements DriverCreatorStrategyInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createDriver(Driver $driver): void
    {
        $admin = $driver->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }
}