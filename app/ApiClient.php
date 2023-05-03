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

    public function getCharacters(int $page = 1): array
    {
        $response = json_decode($this->client->get('character/?page=' . $page)->getBody()->getContents());
        $characters = [];

        foreach ($response->results as $character) {
            $characters[] = $this->createCharacter($character);
        }

        $pageInfo = new Page($response->info);
        return ['characters' => $characters, 'page' => $pageInfo];
    }

    public function getEpisodes(int $page = 1): array
    {
        $response = json_decode($this->client->get('episode/?page=' . $page)->getBody()->getContents());
        $episodes = [];
        foreach ($response->results as $episode) {
            $episodes[] = $this->createEpisode($episode);
        }
        $pageInfo = new Page($response->info);
        return ['episodes' => $episodes, 'page' => $pageInfo];
    }

    public function getLocations(int $page = 1): array
    {
        $response = json_decode($this->client->get('location/?page=' . $page)->getBody()->getContents());
        $locations = [];
        foreach ($response->results as $location) {
            $locations[] = $this->createLocation($location);
        }
        $pageInfo = new Page($response->info);
        return ['locations' => $locations, 'page' => $pageInfo];
    }

    public function getSingleCharacter(int $id): Character
    {
        $response = json_decode($this->client->get('character/' . $id)->getBody()->getContents());
        return $this->createCharacter($response);
    }

    public function getEpisodesById(array $id): array
    {
        $response = json_decode($this->client->get('episode/' . implode(',', $id))->getBody()->getContents());
        $episodes = [];
        if (count($id) === 1) {
            $episodes [] = $this->createEpisode($response);
        } else {
            foreach ($response as $episode) {
                $episodes[] = $this->createEpisode($episode);
            }
        }
        return $episodes;
    }

    public function getSingleEpisode(int $id): Episode
    {
        $response = json_decode($this->client->get('episode/' . $id)->getBody()->getContents());
        return $this->createEpisode($response);
    }


    private function createCharacter(\stdClass $character): Character
    {
        $episodeIds = [];
        foreach ($character->episode as $episode) {
            $episodeIds[] = basename($episode);
        }
        return new Character(
            $character->id,
            $character->name,
            $character->status,
            $character->species,
            $character->origin,
            $character->location,
            $character->image,
            $character->url,
            $episodeIds,
            $this->getSingleEpisode((int)$episodeIds[0])
        );
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