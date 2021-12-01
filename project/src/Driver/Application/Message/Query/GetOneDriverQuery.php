<?php

declare(strict_types=1);

namespace App\Driver\Application\Message\Query;

class GetOneDriverQuery
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
