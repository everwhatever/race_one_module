<?php

namespace App\Results\Dto;

use DateTimeInterface;
use JetBrains\PhpStorm\Pure;

class Race
{
    private string $name;
    private DateTimeInterface $date;
    private int $id;

    /**
     * Race constructor.
     */
    private function __construct(string $name, DateTimeInterface $date, int $id)
    {
        $this->name = $name;
        $this->date = $date;
        $this->id = $id;
    }

    /**
     * @param string $name
     * @param DateTimeInterface $date
     * @param int $id
     * @return $this
     */
    #[Pure] public static function createRace(string $name, DateTimeInterface $date, int $id): self
    {
        return new self($name, $date, $id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}