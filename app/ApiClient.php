<?php declare(strict_types=1);

namespace App;

use App\Models\Episode;
use App\Models\Location;
use App\Models\Page;
use GuzzleHttp\Client;
use App\Models\Character;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://rickandmortyapi.com/api/'
        ]);
    }

    public function getCharacters(int $page, string $name): array
    {
        try {
            if (!Cache::has('characters_' . $name . '_' . $page)) {
                $response = $this->client->get('character/', [
                    'query' => [
                        'name' => $name,
                        'page' => $page,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();
                Cache::save('characters_' . $name . '_' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('characters_' . $name . '_' . $page);
            }

            $characters = json_decode($responseJson);
            $characterCollection = [];
            foreach ($characters->results as $character) {
                $characterCollection[] = $this->createCharacter($character);
            }
            $pageInfo = new Page($characters->info);
            return ['characters' => $characterCollection,
                'currentPage' => $page,
                'page' => $pageInfo,
                'name' => $name];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getEpisodes(int $page): array
    {
        try {
            if (!Cache::has('episodes_' . $page)) {
                $response = $this->client->get('episode/?page=' . $page);
                $responseJson = $response->getBody()->getContents();
                Cache::save('episodes_' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('episodes_' . $page);
            }

            $episodes = json_decode($responseJson);
            $episodeCollection = [];
            foreach ($episodes->results as $episode) {
                $episodeCollection[] = $this->createEpisode($episode);
            }
            $pageInfo = new Page($episodes->info);
            return [
                'episodes' => $episodeCollection,
                'page' => $pageInfo,
                'currentPage' => $page
            ];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getLocations(int $page): array
    {
        try {
            if (!Cache::has('locations_' . $page)) {
                $response = $this->client->get('location/?page=' . $page);
                $responseJson = $response->getBody()->getContents();
                Cache::save('locations_' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('locations_' . $page);
            }

            $locations = json_decode($responseJson);
            $locationCollection = [];
            foreach ($locations->results as $location) {
                $locationCollection[] = $this->createLocation($location);
            }
            $pageInfo = new Page($locations->info);
            return [
                'locations' => $locationCollection,
                'page' => $pageInfo,
                'currentPage' => $page
            ];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getCharactersById(?array $id): array
    {
        if (!$id) {
            return [];
        }

        try {
            $response = json_decode($this->client->get(
                'character/' . implode(',', $id))->getBody()->getContents());
            $characters = [];
            if (count($id) === 1) {
                $characters [] = $this->getSingleCharacter($response->id);
            } else {
                foreach ($response as $character) {
                    $characters[] = $this->getSingleCharacter($character->id);
                }
            }
            return $characters;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getSingleCharacter(int $id): ?Character
    {
        try {
            if (!Cache::has('character_' . $id)) {
                $response = $this->client->get('character/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save('character_' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('character_' . $id);
            }

            return $this->createCharacter(json_decode($responseJson));

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getEpisodesById(array $id): array
    {
        try {
            $response = json_decode($this->client->get(
                'episode/' . implode(',', $id))->getBody()->getContents());
            $episodes = [];
            if (count($id) === 1) {
                $episodes [] = $this->getSingleEpisode($response->id);
            } else {
                foreach ($response as $episode) {
                    $episodes[] = $this->getSingleEpisode($episode->id);
                }
            }
            return $episodes;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getSingleEpisode(int $id): ?Episode
    {
        try {
            if (!Cache::has('episode_' . $id)) {
                $response = $this->client->get('episode/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save('episode_' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('episode_' . $id);
            }

            return $this->createEpisode(json_decode($responseJson));
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getSingleLocation(int $id): ?Location
    {
        if(!$id){
            return null;
        }
        try {
            if (!Cache::has('location_' . $id)) {
                $response = $this->client->get('location/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save('location_' . $id, $responseJson);
            } else {
                $responseJson = Cache::get('location_' . $id);
            }
            return $this->createLocation(json_decode($responseJson));
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    private function createCharacter(stdClass $character): Character
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
            $this->getSingleLocation((int)basename($character->origin->url)),
            $this->getSingleLocation((int)basename($character->location->url)),
            $character->image,
            $character->url,
            $episodeIds,
            $this->getSingleEpisode((int)$episodeIds[0])
        );
    }

    private function createEpisode(stdClass $episode): Episode
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

    private function createLocation(stdClass $location): Location
    {
        $characterIds = [];
        if (empty($location->residents)) {
            $characterIds = null;
        } else {
            foreach ($location->residents as $character) {
                $characterIds[] = basename($character);
            }
        }
        return new Location(
            $location->id,
            $location->name,
            $location->type,
            $location->dimension,
            $characterIds
        );
    }
}