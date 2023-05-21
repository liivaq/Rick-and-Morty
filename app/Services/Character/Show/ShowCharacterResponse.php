<?php declare(strict_types=1);

namespace App\Services\Character\Show;

use App\Models\Character;

class ShowCharacterResponse
{
    private Character $character;
    private array $episodes;

    public function __construct(Character $character, array $episodes)
    {
        $this->character = $character;
        $this->episodes = $episodes;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getEpisodes(): array
    {
        return $this->episodes;
    }

}