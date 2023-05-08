<?php declare(strict_types=1);

namespace App\Models;
class Character
{
    private int $id;
    private string $name;
    private string $status;
    private string $species;
    private ?Location $origin;
    private ?Location $location;
    private string $image;
    private string $url;
    private array $episodeIds;
    private Episode $firstEpisode;

    public function __construct(
        int       $id,
        string    $name,
        string    $status,
        string    $species,
        ?Location $origin,
        ?Location $location,
        string    $image,
        string    $url,
        array     $episodes,
        Episode   $firstEpisode
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
        $this->episodeIds = $episodes;
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

    public function getOrigin(): ?Location
    {
        return $this->origin;
    }

    public function getLocation(): ?Location
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

    public function getEpisodeIds(): array
    {
        return $this->episodeIds;
    }

    public function getFirstEpisode(): Episode
    {
        return $this->firstEpisode;
    }
}