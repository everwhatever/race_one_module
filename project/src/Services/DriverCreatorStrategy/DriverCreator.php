<?php


namespace App\Services\DriverCreatorStrategy;


use App\Entity\Driver;

class DriverCreator
{
    public function create(string $email, string $password, DriverCreatorStrategyInterface $creatorStrategy): Driver
    {
        return $creatorStrategy->createDriver($email, $password);
    }
}