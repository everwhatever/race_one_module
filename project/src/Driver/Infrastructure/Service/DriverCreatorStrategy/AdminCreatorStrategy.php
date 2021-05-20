<?php


namespace App\Driver\Infrastructure\Service\DriverCreatorStrategy;


use App\Driver\Domain\Model\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCreatorStrategy implements DriverCreatorStrategyInterface
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
        $adminDriver = new Driver();
        $adminDriver->setEmail($email);
        $adminDriver->setPassword($this->passwordEncoder->hashPassword($adminDriver, $password));
        $adminDriver->setRoles(['ROLE_USER', 'ROLE_ADMIN']);


        $this->entityManager->persist($adminDriver);
        $this->entityManager->flush();

        return $adminDriver;
    }
}