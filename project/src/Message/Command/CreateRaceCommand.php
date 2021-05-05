<?php

declare(strict_types=1);

namespace App\Message\Command;

class CreateRaceCommand
{
    /** @var int[] */
    private array $driversIds;

    private string $name;

    public function __construct(array $driversIds, string $name)
    {
        $this->driversIds = $driversIds;
        $this->name = $name;
    }

    /**
     * @return int[]
     */
    public function getDriversIds(): array
    {
        return $this->driversIds;
    }

    public function getName(): string
    {
        return $this->name;
    }
}