<?php


namespace App\League\Infrastructure\Service;


use App\Race\Domain\Service\TimeCreator;

class PositionSetter
{
    private TimeCreator $timeService;

    public function __construct(TimeCreator $timeService)
    {
        $this->timeService = $timeService;
    }

    public function setLeaguePositions(array $leagueDriversPoints): array
    {
        asort($leagueDriversPoints);
        $driversPositions = [];
        $position = 1;

        foreach ($leagueDriversPoints as $driver => $points) {
            $driversPositions[$driver] = $position;
            $position++;
        }
        return $driversPositions;
    }

    /**
     * @param array $drivers
     * @param array $races
     * @return array
     */
    public function setDriversLeaguePoints(array $drivers, array $races): array
    {
        $leagueDriversPoints = $this->prepareDriversPointsArray($drivers);

        foreach ($races as $race) {
            $times = $this->timeService->setSimulatedParams($race);

            $leagueDriversPoints = $this->sumDriversPositions($times, $leagueDriversPoints);
        }

        return $leagueDriversPoints;
    }

    /**
     * @param array $drivers
     * @return array
     */
    private function prepareDriversPointsArray(array $drivers): array
    {
        $leagueDriversPoints = [];
        foreach ($drivers as $driver) {
            $leagueDriversPoints[$driver->getEmail()] = 0;
        }
        return $leagueDriversPoints;
    }

    /**
     * @param array $times
     * @param array $leagueDriversPoints
     * @return array
     */
    private function sumDriversPositions(array $times, array $leagueDriversPoints): array
    {
        foreach ($times as $time) {
            $leagueDriversPoints[$time->getDrivers()->getEmail()] =
                $leagueDriversPoints[$time->getDrivers()->getEmail()] + $time->getPosition();
        }
        return $leagueDriversPoints;
    }
}