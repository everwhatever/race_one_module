<?php


namespace App\Results;


class RaceCreatorResult
{
    private ?int $raceId;

    /**
     * RaceCreatorResult constructor.
     * @param int|null $raceId
     */
    public function __construct(?int $raceId)
    {
        $this->raceId = $raceId;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->raceId;
    }

}