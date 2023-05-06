<?php

namespace App\Controllers;

use App\ApiClient;
use App\Core\View;

class LocationController
{
    private ApiClient $client;

    public function __construct()
    {
        $this->client = new ApiClient();
    }

    public function locations(array $vars): View
    {
        $response = $this->client->getLocations((int)$vars['page'] ?? 1);
        return new View('locations', $response);
    }

    public function singleLocation(array $vars): View
    {
        $location = $this->client->getSingleLocation((int)$vars['page'] ?? 1);
        if (!$location) {
            return new View('notFound', []);
        }
        $characters = $this->client->getCharactersById($location->getCharacters());
        return new View('singleLocation', ['location' => $location, 'characters' => $characters]);
    }

}