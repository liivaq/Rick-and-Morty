<?php declare(strict_types=1);

namespace App\Services\Episode\Show;

class ShowEpisodeRequest
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