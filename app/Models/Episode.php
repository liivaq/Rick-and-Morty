<?php declare(strict_types=1);

namespace App\Models;

class Episode
{
    private int $id;
    private string $name;
    private string $airDate;
    private string $episode;
    private array $characterIds;

    public function __construct(int $id, string $name, string $airDate, string $episode, array $characters)
    {
        $this->id = $id;
        $this->name = $name;
        $this->airDate = $airDate;
        $this->episode = $episode;
        $this->characterIds = $characters;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getCharacterIds(): array
    {
        return $this->characterIds;
    }

    public function getEpisode(): string
    {
        return $this->episode;
    }

}