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
        $page =  isset($vars['page']) ? (int) $vars['page'] : 1;
        $response = $this->client->getCharacterPage($page);
        return new View('characters', $response);
    }


    public function singleCharacter(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;
        $character = $this->client->getSingleCharacter($page);
        if (!$character) {
            return new View('notFound', []);
        }
        $episodes = $this->client->getMultipleEpisodesById($character->getEpisodeIds());
        return new View('singleCharacter', ['character' => $character, 'episodes' => $episodes]);
    }

    public function search(): View
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $name = $_GET['name'] ?? '';
        $status =  $_GET['status'] ?? '';
        $gender =  $_GET['gender'] ?? '';

        $characters = $this->client->searchCharacters($page, $name, $status, $gender);
        return new View('characters', $characters);
    }
}