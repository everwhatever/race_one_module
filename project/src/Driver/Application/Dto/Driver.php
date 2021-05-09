<?php


namespace App\Driver\Application\Dto;


use JetBrains\PhpStorm\Pure;

class Driver
{
    private int $id;
    private string $driverEmail;

    private function __construct(int $id, string $driverEmail)
    {
        $this->id = $id;
        $this->driverEmail = $driverEmail;
    }

    #[Pure] public static function create(int $id, string $driverEmail): self
    {
        return new self($id, $driverEmail);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDriverEmail(): string
    {
        return $this->driverEmail;
    }
}