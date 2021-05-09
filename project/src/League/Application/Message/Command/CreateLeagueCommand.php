<?php


namespace App\League\Application\Message\Command;


class CreateLeagueCommand
{
    private array $driversIds;
    private array $racesNames;
    private string $leagueName;

    public function __construct(array $driversIds, array $racesNames, string $leagueName)
    {
        $this->driversIds = $driversIds;
        $this->racesNames = $racesNames;
        $this->leagueName = $leagueName;
    }

    /**
     * @return array
     */
    public function getDriversIds(): array
    {
        return $this->driversIds;
    }

    /**
     * @return array
     */
    public function getRacesNames(): array
    {
        return $this->racesNames;
    }

    /**
     * @return string
     */
    public function getLeagueName(): string
    {
        return $this->leagueName;
    }

}