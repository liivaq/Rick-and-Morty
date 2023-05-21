<?php declare(strict_types=1);

namespace App\Services\Location\Index;

use App\Repositories\Location\RickAndMortyLocationRepository;

class IndexLocationService
{
    private RickAndMortyLocationRepository $repository;

    public function __construct()
    {
        $this->repository = new RickAndMortyLocationRepository();
    }

    public function execute(IndexLocationRequest $request): array
    {
        return $this->repository->getPage($request->getPage());
    }
}