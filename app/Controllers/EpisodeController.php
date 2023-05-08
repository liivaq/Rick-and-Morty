<?php

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class EpisodeController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function episodes(array $vars): View
    {
        $response = $this->client->getEpisodes((int)$vars['page'] ?? 1);
        return new View('episodes', $response);
    }

    public function singleEpisode(array $vars): View
    {
        $episode = $this->client->getSingleEpisode((int)$vars['page'] ?? 1);
        if (!$episode) {
            return new View('notFound', []);
        }
        $characters = $this->client->getCharactersById($episode->getCharacterIds());
        return new View('singleEpisode', ['episode' => $episode, 'characters' => $characters]);
    }


}