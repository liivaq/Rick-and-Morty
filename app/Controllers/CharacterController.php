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
        parse_str(implode('',$vars), $query);

        $page = $_GET['page'] ?? isset($query['page']) ? (int)$query['page'] : 1;
        $name = $_GET['name'] ?? $query['name'] ?? '';
        $status = $_GET['status'] ?? $query['status'] ?? '';
        $gender = $_GET['gender'] ?? $query['gender'] ?? '';

        $response = $this->client->getCharacters($page, $name, $status, $gender);
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
}