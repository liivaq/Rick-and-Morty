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

    public function getCharactersById(?array $id): ?array
    {
        if(!$id){
            return null;
        }
        $response = json_decode($this->client->get('character/' . implode(',', $id))->getBody()->getContents());
        $characters = [];
        if (count($id) === 1) {
            $characters [] = $this->createCharacter($response);
        }else {
            foreach ($response as $character) {
                $characters[] = $this->createCharacter($character);
            }
        }
        return $characters;
    }

    public function getSingleCharacter(int $id = 1): Character
    {
        $response = json_decode($this->client->get('character/' . $id)->getBody()->getContents());
        return $this->createCharacter($response);
    }

    public function getEpisodesById(array $id = [1]): array
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

    public function getSingleLocation(int $id = 1): Location
    {
        $response = json_decode($this->client->get('location/' . $id)->getBody()->getContents());
        return $this->createLocation($response);
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
        $characterIds = [];
        foreach ($episode->characters as $character) {
            $characterIds[] = basename($character);
        }
        return new Episode(
            $episode->id,
            $episode->name,
            $episode->air_date,
            $episode->episode,
            $characterIds
        );
    }

    private function createLocation(\stdClass $episode): Location
    {
        $characterIds = [];
        if(empty($episode->residents)){
            $characterIds = null;
        }else {
            foreach ($episode->residents as $character) {
                $characterIds[] = basename($character);
            }
        }
        return new Location(
            $episode->id,
            $episode->name,
            $episode->type,
            $episode->dimension,
            $characterIds
        );
    }
}