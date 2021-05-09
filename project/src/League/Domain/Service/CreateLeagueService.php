<?php

namespace App\League\Domain\Service;


use App\League\Domain\Model\League;
use App\League\Infrastructure\Service\PositionSetter;
use App\League\Infrastructure\Service\RaceSaver;
use Doctrine\ORM\EntityManagerInterface;

class CreateLeagueService
{
    private EntityManagerInterface $entityManager;
    private RaceSaver $raceSaver;
    private PositionSetter $positionSetter;

    public function __construct(EntityManagerInterface $entityManager,
                                RaceSaver $raceSaver, PositionSetter $positionSetter)
    {
        $this->entityManager = $entityManager;
        $this->raceSaver = $raceSaver;
        $this->positionSetter = $positionSetter;
    }

    public function createLeague(League $league): void
    {
        $this->entityManager->persist($league);
        $drivers = $league->getDrivers()->toArray();
        $races = $league->getRaces()->toArray();

        $this->raceSaver->saveRaces($races, $drivers, $league);

        $leagueDriversPoints = $this->positionSetter->setDriversLeaguePoints($drivers, $races);
        $driversPositions = $this->positionSetter->setLeaguePositions($leagueDriversPoints);

        $league->setPositions($driversPositions);

        $this->entityManager->persist($league);
        $this->entityManager->flush();

    }
}