<?php


namespace App\Services\DriverCreatorStrategy;


use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NormalDriverCreatorStrategy implements DriverCreatorStrategyInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createDriver(string $email, string $password): Driver
    {
        $driver = new Driver();
        $driver->setEmail($email);
        $driver->setPassword($this->passwordEncoder->encodePassword($driver, $password));
        $driver->setRoles(['ROLE_USER']);


        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }
}