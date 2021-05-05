<?php

declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Entity\Race;
use App\Message\Command\CreateRaceCommand;
use App\Repository\DriverRepository;
use App\Results\RaceCreatorResult;
use App\Services\Time\TimeCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateRaceHandler implements MessageHandlerInterface
{

    private EntityManagerInterface $entityManager;
    private DriverRepository $driverRepository;
    private TimeCreator $timeCreator;

    public function __construct(EntityManagerInterface $entityManager, DriverRepository $driverRepository,
                                TimeCreator $timeCreator)
    {
        $this->entityManager = $entityManager;
        $this->driverRepository = $driverRepository;
        $this->timeCreator = $timeCreator;
    }

    public function __invoke(CreateRaceCommand $command): RaceCreatorResult
    {
        $drivers = $this->driverRepository->findByIds($command->getDriversIds());


        $race = Race::create($command->getName(), $drivers);
        $this->entityManager->persist($race);
        $this->entityManager->flush();

        $this->timeCreator->setSimulatedParams($race);

        return new RaceCreatorResult($race->getId());

    }
}