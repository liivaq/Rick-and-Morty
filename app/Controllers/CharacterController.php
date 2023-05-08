<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class CharacterController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function characters(array $vars): View
    {
        $page = $vars['page'] ?? 1;
        $name = $vars['name'] ?? $_GET['name'] ?? '';
        $response = $this->client->getCharacters((int)$page, $name);
        return new View('characters', $response);
    }


    public function singleCharacter(array $vars): View
    {
        $character = $this->client->getSingleCharacter((int)$vars['page'] ?? 1);
        if (!$character) {
            return new View('notFound', []);
        }
        $episodes = $this->client->getEpisodesById($character->getEpisodeIds());
        return new View('singleCharacter', ['character' => $character, 'episodes' => $episodes]);
    }
}