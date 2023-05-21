<?php declare(strict_types=1);

namespace App\Repositories\Character;
use App\Cache;
use App\Models\Character;
use App\Models\Page;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class RickAndMortyCharacterRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://rickandmortyapi.com/api/'
        ]);
    }

    public function getPage(int $page): array
    {
        try {
            $cacheKey = 'characters_' . $page;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('character/', [
                    'query' => [
                        'page' => $page,
                    ]
                ]);

                $responseContents = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContents);
            } else {
                $responseContents = Cache::get($cacheKey);
            }

            $characters = json_decode($responseContents);
            $characterCollection = [];
            foreach ($characters->results as $character) {
                $characterCollection[] = $this->buildModel($character);
            }
            $pageInfo = new Page($characters->info);
            return
                [
                    'characters' => $characterCollection,
                    'currentPage' => $page,
                    'page' => $pageInfo,
                ];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function search(
        int $page,
        string $name,
        string $status,
        string $gender): array
    {
        try {
            $cacheKey = 'characters_' . $page . '_' . $name . '_' . $status . '_' . $gender;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('character/', [
                    'query' => [
                        'page' => $page,
                        'name' => $name,
                        'status' => $status,
                        'gender' => $gender
                    ]
                ]);

                $responseContents = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContents);
            } else {
                $responseContents = Cache::get($cacheKey);
            }

            $characters = json_decode($responseContents);
            $characterCollection = [];
            foreach ($characters->results as $character) {
                $characterCollection[] = $this->buildModel($character);
            }
            $pageInfo = new Page($characters->info);
            return
                [
                    'characters' => $characterCollection,
                    'currentPage' => $page,
                    'page' => $pageInfo,
                    'name' => $name,
                    'gender' => $gender,
                    'status' => $status
                ];
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function getMultipleById(?array $ids): array
    {
        if (!$ids) {
            return [];
        }

        try {
            $characters = [];
            $toFetch = [];
            foreach ($ids as $id) {
                if (!Cache::has('character_' . $id)) {
                    $toFetch[] = $id;
                } else {
                    $characters[] = $this->buildModel(json_decode(Cache::get('character_' . $id)));
                }
            }

            if (!$toFetch) {
                return $characters;
            }

            $response = json_decode($this->client->get(
                'character/' . implode(',', $toFetch))->getBody()->getContents());
            if (count($ids) === 1) {
                $characters [] = $this->buildModel($response);
            } else {
                foreach ($response as $character) {
                    $ch = $this->buildModel($character);
                    $characters [] = $ch;
                    Cache::save('character_' . $character->id, json_encode($character));
                }
            }
            return $characters;
        } catch (GuzzleException $exception) {
            return [];
        }
    }

    public function show(int $id): ?Character
    {
        try {
            $cacheKey = 'character_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('character/' . $id);
                $responseContents = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContents);
            } else {
                $responseContents = Cache::get($cacheKey);
            }

            return $this->buildModel(json_decode($responseContents));

        } catch (GuzzleException $exception) {
            return null;
        }
    }

    private function buildModel(stdClass $character): Character
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
            (int)basename($character->origin->url),
            (int)basename($character->location->url),
            $character->image,
            $character->url,
            $episodeIds,
            (int)$episodeIds[0]
        );
    }

}