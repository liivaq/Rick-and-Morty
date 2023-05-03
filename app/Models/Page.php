<?php

namespace App\Models;

class Page
{
    private int $count;
    private int $pages;
    private ?int $next;
    private ?int $prev;

    public function __construct(\stdClass $pageInfo)
    {
        $this->count = $pageInfo->count;
        $this->pages = $pageInfo->pages;
        $this->next = (int)substr($pageInfo->next, strrpos($pageInfo->next, '=') + 1);
        $this->prev = (int)substr($pageInfo->prev, strrpos($pageInfo->prev, '=') + 1);
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getNext(): ?int
    {
        return $this->next ?? $this->pages;
    }

    public function getPrev(): ?int
    {
        return $this->prev ?? 1;
    }
}