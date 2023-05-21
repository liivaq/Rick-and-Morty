<?php declare(strict_types=1);

namespace App\Services\Character\Search;

class SearchCharacterRequest
{
    private int $page;
    private string $name;
    private string $status;
    private string $gender;

    public function __construct(int $page, string $name, string $status, string $gender)
    {
        $this->page = $page;
        $this->name = $name;
        $this->status = $status;
        $this->gender = $gender;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

}