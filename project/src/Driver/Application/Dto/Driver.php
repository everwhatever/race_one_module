<?php

declare(strict_types=1);

namespace App\Driver\Application\Dto;

class Driver
{
    private int $id;
    
    private string $driverEmail;

    private function __construct(int $id, string $driverEmail)
    {
        $this->id = $id;
        $this->driverEmail = $driverEmail;
    }

    public static function create(int $id, string $driverEmail): self
    {
        return new self($id, $driverEmail);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDriverEmail(): string
    {
        return $this->driverEmail;
    }
}
