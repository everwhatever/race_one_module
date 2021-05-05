<?php


namespace App\Results\Dto;


use JetBrains\PhpStorm\Pure;

class EachDriverResult
{
    private int $position;
    private string $raceName;
    private string $raceTime;
    private int $raceId;

    private function __construct(int $position, string $raceName, string $raceTime, int $raceId)
    {
        $this->position = $position;
        $this->raceName = $raceName;
        $this->raceTime = $raceTime;
        $this->raceId = $raceId;
    }

    #[Pure] public static function create(int $position, string $raceName, string $raceTime, int $raceId): self
    {
        return new self($position, $raceName, $raceTime, $raceId);
    }

    /**
     * @return int
     */
    public function getRaceId(): int
    {
        return $this->raceId;
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
    public function getRaceName(): string
    {
        return $this->raceName;
    }

    /**
     * @return string
     */
    public function getRaceTime(): string
    {
        return $this->raceTime;
    }

}