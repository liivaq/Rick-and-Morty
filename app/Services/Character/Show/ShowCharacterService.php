<?php declare(strict_types=1);

namespace App\Services\Character\Show;

use App\Exceptions\CharacterNotFoundException;
use App\Repositories\Character\RickAndMortyCharacterRepository;
use App\Repositories\Episode\RickAndMortyEpisodeRepository;
use App\Repositories\Location\RickAndMortyLocationRepository;

class ShowCharacterService
{
    private RickAndMortyCharacterRepository $characterRepository;
    private RickAndMortyEpisodeRepository $episodeRepository;
    private RickAndMortyLocationRepository $locationRepository;

    public function __construct(){
        $this->characterRepository = new RickAndMortyCharacterRepository();
        $this->episodeRepository = new RickAndMortyEpisodeRepository();
        $this->locationRepository = new RickAndMortyLocationRepository();
    }

    public function execute(ShowCharacterRequest $request): ShowCharacterResponse
    {
        $character = $this->characterRepository->show($request->getId());
        if(!$character){
            throw new CharacterNotFoundException('Character by id '.$request->getId() . 'not found');
        }
        $character->setOrigin($this->locationRepository->show($character->getOriginId()));
        $character->setLocation($this->locationRepository->show($character->getLocationId()));
        $episodes = $this->episodeRepository->getMultipleEpisodesById($character->getEpisodeIds());

        return new ShowCharacterResponse($character, $episodes);
    }

}