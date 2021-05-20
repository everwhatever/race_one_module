<?php


namespace App\Shared\Domain\Service;


use App\Race\Domain\Model\Race;
use App\Race\Domain\Model\Time;

class LeaguePositionSetter
{
    public function prepareDriversPositionsArray(array $drivers): array
    {
        $racesPositionsSum = [];
        foreach ($drivers as $driver) {
            $racesPositionsSum[$driver->getId()] = 0;
        }

        return $racesPositionsSum;
    }

    public function sumAllRacesPositionsForDrivers(array $races, array $racesPositionsSum): array
    {
        /** @var Race $race */
        foreach ($races as $race) {
            $racesPositionsSum = $this->sumEachPositions($race, $racesPositionsSum);
        }

        return $racesPositionsSum;
    }

    public function assignLeaguePositions(array $racesPositionsSum): array
    {
        $driversLeaguePositions = [];
        $position = 1;

        foreach ($racesPositionsSum as $driverId => $points) {
            $driversLeaguePositions[$driverId] = $position;
            $position++;
        }

        return $driversLeaguePositions;
    }

    private function sumEachPositions(Race $race, array $racesPositionsSum): array
    {
        /** @var Time $time */
        foreach ($race->getTimes()->toArray() as $time) {
            $racesPositionsSum[$time->getDriverId()] = $racesPositionsSum[$time->getDriverId()] + $time->getPosition();
        }

        return $racesPositionsSum;
    }
}