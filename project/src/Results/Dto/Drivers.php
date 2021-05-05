<?php


namespace App\Results\Dto;


class Drivers
{
    private ?array $drivers;

    public function __construct(array $drivers = null)
    {
        $this->drivers = $drivers ? $drivers : null;
    }

    public function addDriver(Driver $driver)
    {
        $this->drivers[] = $driver;
    }

    /**
     * @return array|null
     */
    public function getDrivers(): ?array
    {
        return $this->drivers;
    }

}