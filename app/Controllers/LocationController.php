<?php declare(strict_types=1);

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
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;
        $response = $this->client->getLocationPage($page);
        return new View('locations', $response);
    }

    public function singleLocation(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;
        $location = $this->client->getSingleLocation($page);
        if (!$location) {
            return new View('notFound', []);
        }
        $characters = $this->client->getMultipleCharactersById($location->getCharacterIds());
        return new View('singleLocation', ['location' => $location, 'characters' => $characters]);
    }
}