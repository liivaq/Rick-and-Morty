<?php declare(strict_types=1);

namespace App\Core;

class View
{
    private string $templatePath;
    private array $properties;

    public function __construct(string $templatePath, array $properties)
    {
        $this->properties = $properties;
        $this->templatePath = $templatePath;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}