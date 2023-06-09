<?php declare(strict_types=1);

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
        if (!$this->next) {
            return $this->pages;
        }
        $query = parse_url($this->next, PHP_URL_QUERY);
        parse_str($query, $params);
        return (int )$params['page'];

    }

    public function getPrev(): int
    {
        if (!$this->prev) {
            return 1;
        }
        $query = parse_url($this->prev, PHP_URL_QUERY);
        parse_str($query, $params);

        return (int )$params['page'];
    }
}