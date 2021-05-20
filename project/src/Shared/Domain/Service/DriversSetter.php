<?php


namespace App\Shared\Domain\Service;


use App\Driver\Domain\Model\Driver;
use App\Driver\Infrastructure\Repository\DriverRepository;

class DriversSetter
{
    private DriverRepository $driverRepository;

    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }


    public function getDrivers(array $driversIds): array
    {
        return $this->driverRepository->findByIds($driversIds);
    }

    public function assignLeagueToDrivers(array $drivers, int $leagueId)
    {
        /** @var Driver $driver */
        foreach ($drivers as $driver){
            $driver->addLeague($leagueId);
        }
    }

    public function assignRacesToDrivers(array $drivers, array $races)
    {
        $racesIds = $this->getRacesIds($races);

        /** @var Driver $driver */
        foreach ($drivers as $driver){
            $driver->addRacesIds($racesIds);
        }
    }

    private function getRacesIds(array $races): array
    {
        $racesIds = [];
        foreach ($races as $race){
            $racesIds[] = $race->getId();
        }

        return $racesIds;
    }
}