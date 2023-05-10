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

    public function getCharacters(
        int $page,
        string $name,
        string $status,
        string $gender
    ): array
    {
        try {
            $cacheKey = 'characters_' . $name . '_' . $page . '_'. $gender . '_'. $status;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('character/', [
                    'query' => [
                        'page' => $page,
                        'name' => $name,
                        'status' => $status,
                        'gender' => $gender
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
            }

            $characters = json_decode($responseJson);
            $characterCollection = [];
            foreach ($characters->results as $character) {
                $characterCollection[] = $this->createCharacter($character);
            }
            $pageInfo = new Page($characters->info);
            return
                [
                    'characters' => $characterCollection,
                    'currentPage' => $page,
                    'page' => $pageInfo,
                    'name' => $name,
                    'status' => $status,
                    'gender' => $gender
                ];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getEpisodes(int $page): array
    {
        try {
            $cacheKey = 'episodes_' . $page;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('episode/?page=' . $page);
                $responseJson = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
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
            $cacheKey = 'locations_' . $page;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('location/?page=' . $page);
                $responseJson = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
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

    public function getMultipleCharactersById(?array $id): array
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
            $cacheKey = 'character_' . $id;
            if (!Cache::has( $cacheKey)) {
                $response = $this->client->get('character/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save( $cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
            }

            return $this->createCharacter(json_decode($responseJson));

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getMultipleEpisodesById(array $ids): array
    {
        try {
            $episodes = [];
            $response = json_decode($this->client->get(
                'episode/' . implode(',', $ids))->getBody()->getContents());

            if (count($ids) === 1) {
                $episodes [] = $this->createEpisode($response);
            } else {
                foreach ($response as $ep) {
                    $episodes[] = $this->createEpisode($ep);
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
            $cacheKey = 'episode_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('episode/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
            }

            return $this->createEpisode(json_decode($responseJson));
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getSingleLocation(int $id): ?Location
    {
        if (!$id) {
            return null;
        }
        try {
            $cacheKey = 'location_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('location/' . $id);
                $responseJson = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseJson);
            } else {
                $responseJson = Cache::get($cacheKey);
            }
            return $this->createLocation(json_decode($responseJson));
        } catch (GuzzleException $exception) {
            return null;
        }
    }

    public function getEpisodeCount(): int
    {
        try {
            $response = json_decode($this->client->get('episode/')->getBody()->getContents());
            return $response->info->count;
        } catch (GuzzleException $exception) {
            return 0;
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
            (int)substr($episode->episode, 2, 2),
            (int)substr($episode->episode, 4, 5),
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