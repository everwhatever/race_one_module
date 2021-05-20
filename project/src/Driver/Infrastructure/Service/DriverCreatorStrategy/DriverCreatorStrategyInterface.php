<?php


namespace App\Driver\Infrastructure\Service\DriverCreatorStrategy;


use App\Driver\Domain\Model\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface DriverCreatorStrategyInterface
{
    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder);

    public function createDriver(string $email, string $password): Driver;
}