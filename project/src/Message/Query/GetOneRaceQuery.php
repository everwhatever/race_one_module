<?php


namespace App\Message\Query;


class GetOneRaceQuery
{
    private int $raceId;

    public function __construct(int $raceId)
    {
        $this->raceId = $raceId;
    }

    /**
     * @return int
     */
    public function getRaceId(): int
    {
        return $this->raceId;
    }
}