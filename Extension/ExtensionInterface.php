<?php

declare(strict_types=1);

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Extension;

use Spatie\SchemaOrg\BaseType;

interface ExtensionInterface
{
    /**
     * Extend a Schema create
     *
     * @param BaseType $schema Schema to extend
     * @param BaseType[] $defined all Schema defined
     */
    public function extend(BaseType $schema, array $defined): void;

    /**
     * Return an array with sopported clases. Example:
     *
     * return [Event::class, Article::class];
     *
     * @return array
     */
    public function getTypes(): array;
}
