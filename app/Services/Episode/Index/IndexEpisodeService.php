<?php declare(strict_types=1);

namespace App\Services\Episode\Index;

use App\Repositories\Episode\RickAndMortyEpisodeRepository;

class IndexEpisodeService
{
    private RickAndMortyEpisodeRepository $repository;

    public function __construct(){
        $this->repository = new RickAndMortyEpisodeRepository();
    }

    public function execute(): array
    {
        $episodeCount = $this->repository->getEpisodeCount();
        return $this->repository->getMultipleEpisodesById(range(1,$episodeCount));
    }

}