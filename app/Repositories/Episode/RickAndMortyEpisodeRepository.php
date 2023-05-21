<?php declare(strict_types=1);

namespace App\Repositories\Episode;

use App\Cache;
use App\Models\Episode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class RickAndMortyEpisodeRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://rickandmortyapi.com/api/'
        ]);
    }

    public function getMultipleEpisodesById(array $ids): array
    {
        if (!$ids) {
            return [];
        }

        try {
            $episodes = [];
            $toFetch = [];
            foreach ($ids as $id) {
                if (!Cache::has('episode_' . $id)) {
                    $toFetch[] = $id;
                } else {
                    $episodes[] = $this->buildModel(json_decode(Cache::get('episode_' . $id)));
                }
            }


            if (!$toFetch) {
                usort($episodes, fn($a, $b) => $a->getId() > $b->getId());
                return $episodes;
            }

            $response = json_decode($this->client->get(
                'episode/' . implode(',', $toFetch))->getBody()->getContents());
            if (count($ids) === 1) {
                $episodes [] = $this->buildModel($response);
            } else {
                foreach ($response as $episode) {
                    $ep = $this->buildModel($episode);
                    $episodes[] = $ep;
                    Cache::save('episode_' . $episode->id, json_encode($episode));
                }
            }
        } catch (GuzzleException $exception) {
            return [];
        }
        usort($episodes, fn($a, $b) => $a->getId() > $b->getId());
        return $episodes;
    }

    public function getSingleEpisode(int $id): ?Episode
    {
        try {
            $cacheKey = 'episode_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('episode/' . $id);
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

    public function getEpisodeCount(): int
    {
        try {
            $response = json_decode($this->client->get('episode/')->getBody()->getContents());
            return $response->info->count;
        } catch (GuzzleException $exception) {
            return 0;
        }
    }

    private function buildModel(stdClass $episode): Episode
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

}