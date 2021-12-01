<?php

declare(strict_types=1);

namespace App\Driver\Application\Dto;

use App\Race\Domain\Model\Time;

class EachDriverResult
{
    private ?Time $time;

    public function __construct(Time $time)
    {
        $this->time = $time;
    }

    public function getRaceId(): int
    {
        return $this->time->getRaces()->getId();
    }

    public function getPosition(): int
    {
        return $this->time->getPosition();
    }

    public function getRaceName(): string
    {
        return $this->time->getRaces()->getName();

    }

    public function getRaceTime(): string
    {
        return $this->time->getTime();
    }

}
