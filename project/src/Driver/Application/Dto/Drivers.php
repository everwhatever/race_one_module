<?php

declare(strict_types=1);

namespace App\Driver\Application\Dto;

class Drivers
{
    private ?array $drivers;

    public function __construct(array $drivers = null)
    {
        $this->drivers = $drivers ?? null;
    }

    public function addDriver(Driver $driver): void
    {
        $this->drivers[] = $driver;
    }

    public function getDrivers(): ?array
    {
        return $this->drivers;
    }

}
