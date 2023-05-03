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
        return new View('characters', ['characters' => $response]);
    }
}