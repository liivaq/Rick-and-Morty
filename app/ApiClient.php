<?php declare(strict_types=1);

namespace app;

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

    public function getCharacters(): array
    {
        $response = json_decode($this->client->get('character')->getBody()->getContents());
        $characterCollection = [];
        foreach ($response->results as $character) {
            $characterCollection[] = $this->createModel($character);
        }
        return $characterCollection;
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
            $this->fetchFirstEpisode($character->episode[0])
        );
    }

    private function fetchFirstEpisode($episode): Episode
    {
        $response = json_decode($this->client->get($episode)->getBody()->getContents());
        return new Episode($response->name, $response->air_date, $response->characters);
    }

}