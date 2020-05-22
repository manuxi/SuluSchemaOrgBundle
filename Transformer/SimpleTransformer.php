<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer;

class SimpleTransformer implements TransformerInterface
{
    public function transform($value)
    {
        return $value;
    }
}
