<?php declare(strict_types=1);

namespace App\Services\Location\Show;

use App\Exceptions\LocationNotFoundException;
use App\Repositories\Character\RickAndMortyCharacterRepository;
use App\Repositories\Location\RickAndMortyLocationRepository;

class ShowLocationService
{
    private RickAndMortyLocationRepository $locationRepository;
    private RickAndMortyCharacterRepository $characterRepository;

    public function __construct()
    {
        $this->characterRepository = new RickAndMortyCharacterRepository();
        $this->locationRepository = new RickAndMortyLocationRepository();
    }

    public function execute(ShowLocationRequest $request): ShowLocationResponse
    {
        $location = $this->locationRepository->show($request->getId());
        if(!$location){
            throw new LocationNotFoundException('Location by id '.$request->getId().' not found');
        }
        $characters = $this->characterRepository->getMultipleById($location->getCharacterIds());

        return new ShowLocationResponse($location, $characters);
    }
}