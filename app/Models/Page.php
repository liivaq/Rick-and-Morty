<?php

namespace App\Models;

class Page
{
    private int $count;
    private int $pages;
    private ?string $next;
    private ?string $previous;

    public function __construct(\stdClass $pageInfo)
    {
        $this->count = $pageInfo->count;
        $this->pages = $pageInfo->pages;
        $this->next = $pageInfo->next;
        $this->previous = $pageInfo->prev;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }
}