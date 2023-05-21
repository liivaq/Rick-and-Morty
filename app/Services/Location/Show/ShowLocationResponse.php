<?php declare(strict_types=1);

namespace App\Services\Location\Show;

use App\Models\Location;

class ShowLocationResponse
{
    private Location $location;
    private array $characters;

    public function __construct(Location $location, array $characters)
    {
        $this->location = $location;
        $this->characters = $characters;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }

}