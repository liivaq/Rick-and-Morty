<?php declare(strict_types=1);

namespace App\Services\Episode\Show;

use App\Models\Episode;

class ShowEpisodeResponse
{
    private Episode $episode;
    private array $characters;

    public function __construct(Episode $episode, array $characters)
    {
        $this->episode = $episode;
        $this->characters = $characters;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }
}