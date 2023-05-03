<?php declare(strict_types=1);

namespace App\Models;

class LocationPage
{
    private array $locations;
    private \stdClass $pageInfo;

    public function __construct(array $locations, \stdClass $pageInfo)
    {
        $this->locations = $locations;
        $this->pageInfo = $pageInfo;
    }

    public function getLocations(): array
    {
        return $this->locations;
    }


    public function getPageInfo(): \stdClass
    {
        return $this->pageInfo;
    }
}