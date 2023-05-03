<?php declare(strict_types=1);

namespace app;

use App\Models\CharacterPage;
use App\Models\Episode;
use App\Models\EpisodePage;
use App\Models\Location;
use App\Models\LocationPage;
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
    public function getEpisodes($page = 1): EpisodePage
    {
        $response = json_decode($this->client->get('episode/?page=' . $page)->getBody()->getContents());
        $episodes = $this->fetchEpisodes($response->results);
        return new EpisodePage($episodes, $response->info);
    }

    public function getLocations($page = 1): LocationPage
    {
        $response = json_decode($this->client->get('location/?page=' . $page)->getBody()->getContents());
        $locations = $this->fetchLocations($response->results);
        return new LocationPage($locations, $response->info);
    }

    private function fetchCharacters(array $response): array
    {
        $characters = [];
        foreach ($response as $character) {
            $characters[] = $this->createCharacter($character);
        }
        return $characters;
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

    private function fetchEpisodes(array $response): array
    {
        $episodes = [];
        foreach ($response as $episode) {
            $episodes[] = $this->createEpisode($episode);
        }
        return $episodes;
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

    private function fetchLocations(array $response): array
    {
        $episodes = [];
        foreach ($response as $episode) {
            $episodes[] = $this->createLocation($episode);
        }
        return $episodes;
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