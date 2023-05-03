<?php declare(strict_types=1);

namespace App;

use App\Models\Episode;
use App\Models\Location;
use App\Models\Page;
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

    public function getCharacters($page = 1): array
    {
        $response = json_decode($this->client->get('character/?page=' . $page)->getBody()->getContents());
        $characters = [];
        foreach ($response->results as $character) {
            $characters[] = $this->createCharacter($character);
        }
        $pageInfo = new Page($response->info);
        return ['characters' => $characters, 'page' => $pageInfo];
    }
    public function getEpisodes($page = 1): array
    {
        $response = json_decode($this->client->get('episode/?page=' . $page)->getBody()->getContents());
        $episodes = [];
        foreach ($response->results as $episode) {
            $episodes[] = $this->createEpisode($episode);
        }
        $pageInfo = new Page($response->info);
        return ['episodes' => $episodes, 'page' => $pageInfo];
    }

    public function getLocations($page = 1): array
    {
        $response = json_decode($this->client->get('location/?page=' . $page)->getBody()->getContents());
        $locations = [];
        foreach ($response->results as $location) {
            $locations[] = $this->createLocation($location);
        }
        $pageInfo = new Page($response->info);
        return ['locations' => $locations, 'page' => $pageInfo];
    }


    private function createCharacter(\stdClass $character): Character
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
        return $this->createEpisode($response);
    }


    private function createEpisode(\stdClass $episode): Episode
    {
        return new Episode(
            $episode->id,
            $episode->name,
            $episode->air_date,
            $episode->episode,
            $episode->characters
        );
    }

    private function createLocation(\stdClass $episode): Location
    {
        return new Location(
            $episode->id,
            $episode->name,
            $episode->type,
            $episode->dimension,
            $episode->residents
        );
    }
}