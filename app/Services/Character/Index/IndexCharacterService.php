<?php declare(strict_types=1);

namespace App\Services\Character\Index;

use App\Exceptions\PageNotFoundException;
use App\Models\Character;
use App\Repositories\Character\RickAndMortyCharacterRepository;
use App\Repositories\Episode\RickAndMortyEpisodeRepository;
use App\Repositories\Location\RickAndMortyLocationRepository;

class IndexCharacterService
{
    private RickAndMortyCharacterRepository $characterRepository;
    private RickAndMortyEpisodeRepository $episodeRepository;
    private RickAndMortyLocationRepository $locationRepository;

    public function __construct()
    {
        $this->characterRepository = new RickAndMortyCharacterRepository();
        $this->episodeRepository = new RickAndMortyEpisodeRepository();
        $this->locationRepository = new RickAndMortyLocationRepository();
    }

    public function execute(IndexCharacterRequest $request): array
    {
        $characters = $this->characterRepository->getPage($request->getPage());

        if(empty($characters)){
            throw new PageNotFoundException ('Character Page '.$request->getPage(). 'not Found');
        }

        /** @var Character $character */
        foreach ($characters['characters'] as $character){
            $firstEpisode = $this->episodeRepository->getSingleEpisode($character->getFirstEpisodeId());
            $location = $this->locationRepository->show($character->getLocationId()) ?? null;
            $origin = $this->locationRepository->show($character->getOriginId()) ?? null;
            $character->setFirstEpisode($firstEpisode);
            $character->setLocation($location);
            $character->setOrigin($origin);
        }
        return $characters;
    }
}