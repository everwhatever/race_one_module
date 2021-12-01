<?php

declare(strict_types=1);

namespace App\Driver\Application\Dto;

class DriverResults
{
    private array $results = [];
    
    private string $driverEmail;

    public function __construct(string $driverEmail)
    {
        $this->driverEmail = $driverEmail;
    }

    public function addResult(EachDriverResult $result): void
    {
        if (!in_array($result, $this->results)) {
            $this->results[] = $result;
        }
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
