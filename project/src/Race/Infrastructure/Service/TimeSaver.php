<?php


namespace App\Race\Infrastructure\Service;


use Doctrine\ORM\EntityManagerInterface;

class TimeSaver
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $times
     * @return array
     */
    public function addTimesToDatabase(array $times): array
    {
        foreach ($times as $time) {
            $this->entityManager->persist($time);
            $this->entityManager->flush();
        }

        return $times;
    }
}