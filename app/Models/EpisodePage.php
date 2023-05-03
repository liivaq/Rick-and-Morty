<?php declare(strict_types=1);

namespace App\Models;

class EpisodePage
{
    private array $episodes;
    private \stdClass $pageInfo;

    public function __construct(array $episodes, \stdClass $pageInfo)
    {
        $this->episodes = $episodes;
        $this->pageInfo = $pageInfo;
    }

    public function getEpisodes(): array
    {
        return $this->episodes;
    }


    public function getPageInfo(): \stdClass
    {
        return $this->pageInfo;
    }
}