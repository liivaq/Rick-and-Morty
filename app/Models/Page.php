<?php

namespace App\Models;

class Page
{
    private int $count;
    private int $pages;
    private string $next;
    private string $prev;

    public function __construct(\stdClass $pageInfo)
    {
        $this->count = $pageInfo->count;
        $this->pages = $pageInfo->pages;
        $this->next = basename($pageInfo->next);
        $this->prev = basename($pageInfo->prev);
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getNext(): string
    {
        return $this->next ?? $this->pages;
    }

    public function getPrev(): string
    {
        return $this->prev ?? '1';
    }
}