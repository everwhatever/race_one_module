<?php

declare(strict_types=1);

namespace App\Driver\Application\Message\Command;

use App\Driver\Infrastructure\Service\DriverCreatorStrategy\DriverCreatorStrategyInterface;

class CreateDriverCommand
{
    private string $email;
    
    private string $password;
    
    private DriverCreatorStrategyInterface $strategy;

    public function __construct(string $email, string $password, DriverCreatorStrategyInterface $strategy)
    {
        $this->email = $email;
        $this->password = $password;
        $this->strategy = $strategy;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getStrategy(): DriverCreatorStrategyInterface
    {
        return $this->strategy;
    }

}
