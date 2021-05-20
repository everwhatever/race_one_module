<?php


namespace App\Race\Application\Dto;


class RaceResults
{
    private array $results = [];
    private string $raceName;

    public function __construct(string $raceName)
    {
        $this->raceName = $raceName;
    }

    public function getRaceName(): string
    {
        return $this->raceName;
    }

    public function addResult(EachRaceResult $result)
    {
        if (!in_array($result, $this->results)) {
            $this->results[] = $result;
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }
}