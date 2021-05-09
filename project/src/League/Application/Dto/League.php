<?php


namespace App\League\Application\Dto;


use JetBrains\PhpStorm\Pure;

class League
{
    private string $name;
    private array $driversPositions;
    private int $id;

    private function __construct(int $id, string $name, array $driversPositions)
    {
        $this->name = $name;
        $this->driversPositions = $driversPositions;
        $this->id = $id;
    }

    #[Pure] public static function create(int $id, string $name, array $driversPositions): self
    {
        return new self($id, $name, $driversPositions);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getDriversPositions(): array
    {
        return $this->driversPositions;
    }

}