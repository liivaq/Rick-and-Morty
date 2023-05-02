<?php declare(strict_types=1);

namespace App\Models;
class Character
{
    private string $name;
    private string $status;
    private string $species;
    private \stdClass $origin;
    private \stdClass $location;

    private string $image;
    private string $url;
    private Episode $episode;

    public function __construct(
        string    $name,
        string    $status,
        string    $species,
        \stdClass $origin,
        \stdClass $location,
        string    $image,
        string    $url,
        Episode   $episode
    )
    {
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->origin = $origin;
        $this->location = $location;
        $this->image = $image;
        $this->url = $url;
        $this->episode = $episode;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getOrigin(): \stdClass
    {
        return $this->origin;
    }

    public function getLocation(): \stdClass
    {
        return $this->location;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

}