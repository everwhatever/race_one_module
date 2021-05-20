<?php

namespace App\Race\Application\Dto;


class EachRaceResult
{
    private int $position;
    private string $time;
    private string $driverEmail;

    public function __construct(int $position, string $time, string $driverEmail)
    {
        $this->position = $position;
        $this->time = $time;
        $this->driverEmail = $driverEmail;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getDriverEmail(): string
    {
        return $this->driverEmail;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }
}