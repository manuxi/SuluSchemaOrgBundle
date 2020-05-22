<?php

declare(strict_types=1);

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation;

class SchemaAttributes
{
    private array $schemaAttributes = [];

    /**
     * @param string $key
     * @param mixed $data
     */
    public function addAttribute(string $key, $data): void
    {
        if (null === $data) {
            throw new \RuntimeException('Data must not be null for key ' . $key);
        }
        $this->schemaAttributes[$key][] = $data;
    }
    public function getAttributes(): array
    {
        return $this->schemaAttributes;
    }
}
