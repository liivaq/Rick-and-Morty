<?php

namespace App\Models;

class Page
{
    private int $count;
    private int $pages;
    private ?string $next;
    private ?string $prev;

    public function __construct(\stdClass $pageInfo)
    {
        $this->count = $pageInfo->count;
        $this->pages = $pageInfo->pages;
        $this->next = $pageInfo->next;
        $this->prev = $pageInfo->prev;

        //$this->next = substr($pageInfo->next, strrpos($pageInfo->next, '=') + 1)

        //$this->prev = substr($pageInfo->prev, strrpos($pageInfo->prev, '=') + 1);
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getNext(): int
    {
        if(!$this->next){
            return $this->pages;
        }
        return (int)substr($this->next, strrpos($this->next, '=') + 1);

    }

    public function getPrev(): int
    {
        if(!$this->prev){
            return 1;
        }
        return (int)substr($this->prev, strrpos($this->prev, '=') + 1);
    }
}