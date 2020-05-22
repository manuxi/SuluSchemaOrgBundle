<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer;

interface TransformerInterface
{
    /**
     * Transform Sulu ContentType value in SchemaOrg implementation
     *
     * @param mixed $value
     * @return mixed
     */
    public function transform($value);
}
