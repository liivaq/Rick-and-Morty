<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class Controller
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function characters($page = 1): View
    {
        $response = $this->client->getCharacters($page);
        return new View('characters', $response);
    }

    public function episodes($page = 1): View
    {
        $response = $this->client->getEpisodes($page);
        return new View('episodes', $response);
    }

    public function locations($page = 1): View
    {
        $response = $this->client->getLocations($page);
        return new View('locations', $response);
    }

    public function singleCharacter($id = 1): View
    {
        $character = $this->client->getSingleCharacter($id);
        $episodes = $this->client->getEpisodesById($character->getEpisodes());
        return new View('singleCharacter', ['character' => $character, 'episodes' => $episodes]);
    }
}