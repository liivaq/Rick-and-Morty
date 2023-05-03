<?php declare(strict_types=1);

namespace App\Models;
class Character
{
    private int $id;
    private string $name;
    private string $status;
    private string $species;
    private \stdClass $origin;
    private \stdClass $location;
    private string $image;
    private string $url;
    private array $episodes;
    private Episode $firstEpisode;

    public function __construct(
        int $id,
        string    $name,
        string    $status,
        string    $species,
        \stdClass $origin,
        \stdClass $location,
        string    $image,
        string    $url,
        array     $episodes,
        Episode  $firstEpisode
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->origin = $origin;
        $this->location = $location;
        $this->image = $image;
        $this->url = $url;
        $this->episodes = $episodes;
        $this->firstEpisode = $firstEpisode;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getEpisodes(): array
    {
        return $this->episodes;
    }

    public function getFirstEpisode(): Episode
    {
        return $this->firstEpisode;
    }
}