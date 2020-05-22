<?php

declare(strict_types=1);

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Model;

use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Schema;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Exception\SchemaTypeNotImplemented;

class SchemaModel
{
    private string $schema;
    private array $properties;

    public function __construct(string $schema)
    {
        $this->schema = $schema;
    }

    public function getSchemaType(): string
    {
        return $this->schema;
    }

    /**
     * @param string $property
     * @param mixed $value
     */
    public function setProperty(string $property, $value): void
    {
        $this->properties[$property] = $value;
    }

    /**
     * @throws SchemaTypeNotImplemented
     */
    public function buildSchema(): BaseType
    {
        $callback = [Schema::class, $this->schema];

        if (!is_callable($callback)) {
            throw new SchemaTypeNotImplemented(sprintf('Schema %1$s not implemented, check https://schema.org/%1$s', $this->schema));
        }
        $schema = call_user_func($callback);

        foreach ($this->properties as $property => $value) {
            $schema->$property($value);
        }
        return $schema;
    }
}
