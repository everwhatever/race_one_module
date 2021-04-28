<?php


namespace App\Services\DriverCreatorStrategy;


use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;

interface DriverCreatorStrategyInterface
{
    public function __construct(EntityManagerInterface $entityManager);

    public function createDriver(Driver $driver): void;
}