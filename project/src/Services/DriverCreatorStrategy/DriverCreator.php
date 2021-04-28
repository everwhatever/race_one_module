<?php


namespace App\Services\DriverCreatorStrategy;




use App\Entity\Driver;

class DriverCreator
{
    private DriverCreatorStrategyInterface $driverCreator;

    public function __construct(DriverCreatorStrategyInterface $driverCreator)
    {
        $this->driverCreator = $driverCreator;
    }

    public function create(Driver $driver)
    {
        $this->driverCreator->createDriver($driver);
    }
}