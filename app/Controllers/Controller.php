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

    public function characters(array $vars): View
    {
        $page = $vars['page'] ?? 1;
        $name = $_GET['name'] ?? '';
        $response = $this->client->getCharacters((int)$page, $name);
        if (!$response) {
            return $this->notFound();
        }
        return new View('characters', $response);
    }

    public function episodes(array $vars): View
    {
        $response = $this->client->getEpisodes((int)$vars['page'] ?? 1);
        if (!$response) {
            return $this->notFound();
        }
        return new View('episodes', $response);
    }

    public function locations(array $vars): View
    {
        $response = $this->client->getLocations((int)$vars['page'] ?? 1);
        if (!$response) {
            return $this->notFound();
        }
        return new View('locations', $response);
    }

    public function singleCharacter(array $vars): View
    {
        $character = $this->client->getSingleCharacter((int)$vars['page'] ?? 1);
        if (!$character) {
            return $this->notFound();
        }
        $episodes = $this->client->getEpisodesById($character->getEpisodes());
        return new View('singleCharacter', ['character' => $character, 'episodes' => $episodes]);
    }

    public function singleEpisode(array $vars): View
    {
        $episode = $this->client->getSingleEpisode((int)$vars['page'] ?? 1);
        if (!$episode) {
            return $this->notFound();
        }
        $characters = $this->client->getCharactersById($episode->getCharacters());
        return new View('singleEpisode', ['episode' => $episode, 'characters' => $characters]);
    }

    public function singleLocation(array $vars): View
    {
        $location = $this->client->getSingleLocation((int)$vars['page'] ?? 1);
        if (!$location) {
            $this->notFound();
        }
        $characters = $this->client->getCharactersById($location->getCharacters());
        return new View('singleLocation', ['location' => $location, 'characters' => $characters]);
    }

    private function notFound(): View
    {
        return new View('notFound', []);
    }
}