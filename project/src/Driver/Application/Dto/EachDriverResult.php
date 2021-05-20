<?php


namespace App\Driver\Application\Dto;

use App\Race\Domain\Model\Time;
use JetBrains\PhpStorm\Pure;

class EachDriverResult
{

    private ?Time $time;

    public function __construct(Time $time)
    {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getRaceId(): int
    {
        return $this->time->getRaces()->getId();
    }

    /**
     * @return int
     */
    #[Pure] public function getPosition(): int
    {
        return $this->time->getPosition();
    }

    /**
     * @return string
     */
    public function getRaceName(): string
    {
        return $this->time->getRaces()->getName();

    }

    /**
     * @return string
     */
    #[Pure] public function getRaceTime(): string
    {
        return $this->time->getTime();
    }

}