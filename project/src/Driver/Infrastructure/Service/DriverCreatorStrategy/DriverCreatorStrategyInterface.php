<?php


namespace App\Driver\Infrastructure\Service\DriverCreatorStrategy;


use App\Driver\Domain\Model\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface DriverCreatorStrategyInterface
{
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder);

    public function createDriver(string $email, string $password): Driver;
}