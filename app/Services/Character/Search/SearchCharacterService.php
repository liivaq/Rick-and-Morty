<?php declare(strict_types=1);

namespace App\Services\Character\Search;

use App\Repositories\Character\RickAndMortyCharacterRepository;

class SearchCharacterService
{
    private RickAndMortyCharacterRepository $repository;

    public function __construct(){
        $this->repository = new RickAndMortyCharacterRepository();
    }

    public function execute(SearchCharacterRequest $request): array
    {
        return $this->repository->search(
            $request->getPage(),
            $request->getName(),
            $request->getStatus(),
            $request->getGender()
        );
    }

}