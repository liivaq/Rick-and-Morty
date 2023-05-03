<?php declare(strict_types=1);

namespace app;

use App\Models\CharacterPage;
use App\Models\Episode;
use GuzzleHttp\Client;
use App\Models\Character;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://rickandmortyapi.com/api/'
        ]);
    }

    public function getCharacters($page = 1): CharacterPage
    {
        $response = json_decode($this->client->get('character/?page=' . $page)->getBody()->getContents());
        $characters = $this->fetchCharacters($response->results);
        return new CharacterPage($characters, $response->info);
    }

    private function fetchCharacters(array $response): array
    {
        $characters = [];
        foreach ($response as $character) {
            $characters[] = $this->createModel($character);
        }
        return $characters;
    }

    private function createModel(\stdClass $character): Character
    {
        return new Character(
            $character->name,
            $character->status,
            $character->species,
            $character->origin,
            $character->location,
            $character->image,
            $character->url,
            $character->episode,
            $this->fetchFirstEpisode($character->episode[0])
        );
    }

    private function fetchFirstEpisode(string $episode): Episode
    {
        $response = json_decode($this->client->get($episode)->getBody()->getContents());
        return new Episode($response->name, $response->air_date, $response->characters);
    }
}