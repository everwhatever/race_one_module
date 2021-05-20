<?php


namespace App\Shared\Domain\Service;


use App\League\Domain\Model\League;
use Doctrine\ORM\EntityManagerInterface;

class LeagueCreator
{
    private EntityManagerInterface $entityManager;
    private RacesSetter $racesSetter;
    private DriversSetter $driversSetter;
    private LeaguePositionSetter $leaguePositionSetter;

    public function __construct(EntityManagerInterface $entityManager, DriversSetter $driversSetter,
                                RacesSetter $racesSetter, LeaguePositionSetter $leaguePositionSetter)
    {
        $this->entityManager = $entityManager;
        $this->racesSetter = $racesSetter;
        $this->driversSetter = $driversSetter;
        $this->leaguePositionSetter = $leaguePositionSetter;
    }

    public function createLeague(array $driversIds, array $racesNames, string $leagueName): League
    {
        $league = new League();
        $league->setName($leagueName);
        $this->entityManager->persist($league);

        $races = $this->setRaces($racesNames, $driversIds, $league);
        $drivers = $this->setDrivers($driversIds, $league, $races);
        $this->setLeaguePositions($league, $races, $drivers);

        $this->entityManager->flush();

        return $league;
    }


    private function setRaces(array $racesNames, array $driversIds, League $league): array
    {
        $races = $this->racesSetter->createRaces($racesNames, $driversIds);
        $this->racesSetter->assignRacesToLeague($races, $league);

        return $races;
    }


    private function setDrivers(array $driversIds, League $league, array $races): array
    {
        $drivers = $this->driversSetter->getDrivers($driversIds);
        $this->driversSetter->assignLeagueToDrivers($drivers, $league->getId());
        $this->driversSetter->assignRacesToDrivers($drivers, $races);
        $league->addDriversIds($driversIds);

        return $drivers;
    }

    private function setLeaguePositions(League $league, array $races, array $drivers)
    {
        $racesPositionsSum = $this->leaguePositionSetter->prepareDriversPositionsArray($drivers);
        $racesPositionsSum = $this->leaguePositionSetter->sumAllRacesPositionsForDrivers($races, $racesPositionsSum);

        asort($racesPositionsSum);
        $driversLeaguePositions = $this->leaguePositionSetter->assignLeaguePositions($racesPositionsSum);
        $league->setPositions($driversLeaguePositions);
    }
}