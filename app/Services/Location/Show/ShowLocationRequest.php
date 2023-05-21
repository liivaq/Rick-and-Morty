<?php declare(strict_types=1);

namespace App\Services\Location\Show;

class ShowLocationRequest
{
    private int $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}