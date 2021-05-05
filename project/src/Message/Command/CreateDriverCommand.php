<?php


namespace App\Message\Command;


use App\Services\DriverCreatorStrategy\DriverCreatorStrategyInterface;

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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return DriverCreatorStrategyInterface
     */
    public function getStrategy(): DriverCreatorStrategyInterface
    {
        return $this->strategy;
    }

}