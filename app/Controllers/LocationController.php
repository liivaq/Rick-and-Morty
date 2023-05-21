<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\LocationNotFoundException;
use App\Services\Location\Index\IndexLocationRequest;
use App\Services\Location\Index\IndexLocationService;
use App\Services\Location\Show\ShowLocationRequest;
use App\Services\Location\Show\ShowLocationService;

class LocationController
{
    public function locations(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;

        $service = new IndexLocationService();
        $response = $service->execute(new IndexLocationRequest($page));

        return new View('locations', $response);
    }

    public function singleLocation(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;

        try {
            $service = new ShowLocationService();
            $response = $service->execute(new ShowLocationRequest($page));
            return new View(
                'singleLocation',
                [
                    'location' => $response->getLocation(),
                    'characters' => $response->getCharacters()
                ]);
        }catch (LocationNotFoundException $e){
            return new View('notFound', []);
        }
    }
}