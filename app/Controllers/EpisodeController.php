<?php declare(strict_types=1);

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

    public function allEpisodes(): View
    {
        $episodeCount = $this->client->getEpisodeCount();
        $response = $this->client->getMultipleEpisodesById(range(1,$episodeCount));
        return new View('episodes', ['episodes' => $response]);
    }

    public function singleEpisode(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;
        $episode = $this->client->getSingleEpisode($page);
        if (!$episode) {
            return new View('notFound', []);
        }
        $characters = $this->client->getMultipleCharactersById($episode->getCharacterIds());
        return new View('singleEpisode', ['episode' => $episode, 'characters' => $characters]);
    }

}