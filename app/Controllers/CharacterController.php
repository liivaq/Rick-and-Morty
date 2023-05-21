<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\CharacterNotFoundException;
use App\Exceptions\PageNotFoundException;
use App\Services\Character\Index\IndexCharacterRequest;
use App\Services\Character\Index\IndexCharacterService;
use App\Services\Character\Search\SearchCharacterRequest;
use App\Services\Character\Search\SearchCharacterService;
use App\Services\Character\Show\ShowCharacterRequest;
use App\Services\Character\Show\ShowCharacterService;

class CharacterController
{
    public function all(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;

        try {
            $service = new IndexCharacterService();
            $response = $service->execute(new IndexCharacterRequest($page));

            return new View('characters', $response);
        }catch (PageNotFoundException $e){
            return new View ('notFound', []);
        }
    }

    public function show(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;

        try {
            $service = new ShowCharacterService();
            $response = $service->execute(new ShowCharacterRequest($page));
            return new View(
                'singleCharacter',
                [
                    'character' => $response->getCharacter(),
                    'episodes' => $response->getEpisodes()
                ]);
        } catch (CharacterNotFoundException $e) {
            return new View ('notFound', []);
        }
    }

    public function search(): View
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $name = trim($_GET['name']) ?? '';
        $status = $_GET['status'] ?? '';
        $gender = $_GET['gender'] ?? '';

        $service = new SearchCharacterService();
        $characters = $service->execute(new SearchCharacterRequest($page, $name, $status, $gender));

        return new View('characters', $characters);
    }
}