<?php


namespace App\Results\Dto;


class DriverResults
{
    private array $results;
    private string $driverEmail;

    public function __construct(string $driverEmail, array $results = null)
    {
        $this->results = $results ? $results : [];
        $this->driverEmail = $driverEmail;
    }

    public function addResult(EachDriverResult $result): void
    {
        $this->results[] = $result;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getDriverEmail(): string
    {
        return $this->driverEmail;
    }

}