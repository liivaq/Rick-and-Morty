<?php declare(strict_types=1);

namespace App\Services\Character\Index;

class IndexCharacterRequest
{
    private int $page;

    public function __construct(int $page){
        $this->page = $page;
    }

    public function getPage(): int
    {
        return $this->page;
    }


}