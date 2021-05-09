<?php


namespace App\Driver\Infrastructure\Service\DriverCreatorStrategy;


use App\Driver\Domain\Model\Driver;

class DriverCreator
{
    public function create(string $email, string $password, DriverCreatorStrategyInterface $creatorStrategy): Driver
    {
        return $creatorStrategy->createDriver($email, $password);
    }
}