<?php declare(strict_types=1);

namespace App\Models;

class Location
{
    private int $id;
    private string $name;
    private string $type;
    private string $dimension;
    private ?array $characters;

    public function __construct(int $id, string $name, string $type, string $dimension, ?array $characters)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
        $this->characters = $characters;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCharacters(): ?array
    {
        return $this->characters;
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

}