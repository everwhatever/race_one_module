<?php


namespace App\Results\Dto;


class Leagues
{
    private ?array $leagues;

    public function __construct(array $leagues = null)
    {
        $this->leagues = $leagues ? $leagues : [];
    }

    public function addLeague(League $league): void
    {
        $this->leagues[] = $league;
    }

    /**
     * @return array
     */
    public function getLeagues(): array
    {
        return $this->leagues;
    }
}