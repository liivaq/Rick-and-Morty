<?php declare(strict_types=1);

namespace App\Services\Episode\Show;

use App\Exceptions\EpisodeNotFoundException;
use App\Repositories\Character\RickAndMortyCharacterRepository;
use App\Repositories\Episode\RickAndMortyEpisodeRepository;

class ShowEpisodeService
{
    private RickAndMortyEpisodeRepository $episodeRepository;
    private RickAndMortyCharacterRepository $characterRepository;

    public function __construct()
    {
        $this->episodeRepository = new RickAndMortyEpisodeRepository();
        $this->characterRepository = new RickAndMortyCharacterRepository();
    }

    public function execute(ShowEpisodeRequest $request): ShowEpisodeResponse
    {

        $episode = $this->episodeRepository->getSingleEpisode($request->getId());
        if(!$episode){
            throw new EpisodeNotFoundException('Episode by id '.$request->getId().'not found');
        }

        $characters = $this->characterRepository->getMultipleById($episode->getCharacterIds());

        return new ShowEpisodeResponse($episode, $characters);

    }
}