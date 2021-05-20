<?php

declare(strict_types=1);

namespace App\Race\Application\MessageHandler\Command;

use App\Race\Application\Dto\RaceCreatorResult;
use App\Race\Application\Message\Command\CreateRaceCommand;
use App\Race\Domain\Model\Race;
use App\Shared\Domain\Service\TimeCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateRaceHandler implements MessageHandlerInterface
{

    private EntityManagerInterface $entityManager;
    private TimeCreator $timeCreator;

    public function __construct(EntityManagerInterface $entityManager, TimeCreator $timeCreator)
    {
        $this->entityManager = $entityManager;
        $this->timeCreator = $timeCreator;
    }

    public function __invoke(CreateRaceCommand $command): RaceCreatorResult
    {
        $race = Race::create($command->getName(), $command->getDriversIds());
        $this->entityManager->persist($race);
        $this->entityManager->flush();

        $this->timeCreator->setSimulatedParams($race);

        return new RaceCreatorResult($race->getId());

    }
}