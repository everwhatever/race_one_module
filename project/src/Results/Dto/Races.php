<?php

namespace App\Results\Dto;


class Races
{
    private ?array $races;

    /**
     * Races constructor.
     */
    public function __construct(array $races = null)
    {
        $this->races = $races ? $races : [];
    }

    public function addRace(Race $race): void
    {
        $this->races[] = $race;
    }

    public function getRaces(): array
    {
        return $this->races;
    }
}