<?php declare(strict_types=1);

namespace App\Models;
class Character
{
    private int $id;
    private string $name;
    private string $status;
    private string $species;
    private int $originId;
    private int $locationId;
    private string $image;
    private string $url;
    private array $episodeIds;
    private int $firstEpisodeId;
    private ?Episode $firstEpisode = null;
    private ?Location $location = null;
    private ?Location $origin = null;

    public function __construct(
        int    $id,
        string $name,
        string $status,
        string $species,
        int    $originId,
        int    $locationId,
        string $image,
        string $url,
        array  $episodes,
        int    $firstEpisodeId
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->originId = $originId;
        $this->locationId = $locationId;
        $this->image = $image;
        $this->url = $url;
        $this->episodeIds = $episodes;
        $this->firstEpisodeId = $firstEpisodeId;
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

    public function getOriginId(): int
    {
        return $this->originId;
    }

    public function getLocationId(): int
    {
        return $this->locationId;
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

    public function getFirstEpisodeId(): int
    {
        return $this->firstEpisodeId;
    }

    public function setFirstEpisode(Episode $firstEpisode): void
    {
        $this->firstEpisode = $firstEpisode;
    }

    public function getFirstEpisode(): ?Episode
    {
        return $this->firstEpisode;
    }

    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setOrigin(?Location $origin): void
    {
        $this->origin = $origin;
    }

    public function getOrigin(): ?Location
    {
        return $this->origin;
    }
}