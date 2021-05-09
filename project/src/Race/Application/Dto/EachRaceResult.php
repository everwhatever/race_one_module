<?php


namespace App\Race\Application\Dto;


use JetBrains\PhpStorm\Pure;

class EachRaceResult
{
    private int $position;
    private string $driverEmail;
    private string $time;

    private function __construct(int $position, string $driverEmail, string $time)
    {
        $this->position = $position;
        $this->driverEmail = $driverEmail;
        $this->time = $time;
    }

    #[Pure] public static function create(int $position, string $driverEmail, string $time): self
    {
        return new self($position, $driverEmail, $time);
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