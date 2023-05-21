<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Exceptions\EpisodeNotFoundException;
use App\Services\Episode\Index\IndexEpisodeService;
use App\Services\Episode\Show\ShowEpisodeRequest;
use App\Services\Episode\Show\ShowEpisodeService;

class EpisodeController
{
    public function allEpisodes(): View
    {
        $service = new IndexEpisodeService();
        $response = $service->execute();
        return new View('episodes', ['episodes' => $response]);
    }

    public function singleEpisode(array $vars): View
    {
        $page = isset($vars['page']) ? (int)$vars['page'] : 1;

        try {
            $service = new ShowEpisodeService();
            $response = $service->execute(new ShowEpisodeRequest($page));
            return new View(
                'singleEpisode',
                [
                    'episode' => $response->getEpisode(),
                    'characters' => $response->getCharacters()
                ]);
        } catch (EpisodeNotFoundException $e) {
            return new View('notFound', []);
        }
    }
}