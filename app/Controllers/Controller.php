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

    public function characters(): View
    {
        $response = $this->client->getCharacters();
        return new View('characters', ['characters' => $response]);
    }

}