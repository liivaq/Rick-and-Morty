<?php declare(strict_types=1);

namespace App\Repositories\Location;

use App\Cache;
use App\Models\Location;
use App\Models\Page;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class RickAndMortyLocationRepository
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
            $cacheKey = 'locations_' . $page;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('location/?page=' . $page);
                $responseContents = $response->getBody()->getContents();
                Cache::save($cacheKey, $responseContents);
            } else {
                $responseContents = Cache::get($cacheKey);
            }

            $locations = json_decode($responseContents);
            $locationCollection = [];
            foreach ($locations->results as $location) {
                $locationCollection[] = $this->buildModel($location);
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

    public function show(int $id): ?Location
    {
        if (!$id) {
            return null;
        }
        try {
            $cacheKey = 'location_' . $id;
            if (!Cache::has($cacheKey)) {
                $response = $this->client->get('location/' . $id);
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

    private function buildModel(stdClass $location): Location
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