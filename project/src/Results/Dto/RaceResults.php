<?php


namespace App\Results\Dto;


class RaceResults
{
    private ?array $results;
    private string $raceName;

    /**
     * Races constructor.
     */
    public function __construct(string $raceName, array $results = null)
    {
        $this->results = $results ? $results : [];
        $this->raceName = $raceName;
    }

    /**
     * @return string
     */
    public function getRaceName(): string
    {
        return $this->raceName;
    }

    public function addResult(EachRaceResult $result): void
    {
        $this->results[] = $result;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}