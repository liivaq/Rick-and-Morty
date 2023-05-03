<?php

namespace App\Models;

class CharacterPage
{
    private array $characters;
    private \stdClass $pageInfo;

    public function __construct(array $characters, \stdClass $pageInfo)
    {
        $this->characters = $characters;
        $this->pageInfo = $pageInfo;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }


    public function getPageInfo(): \stdClass
    {
        return $this->pageInfo;
    }


}