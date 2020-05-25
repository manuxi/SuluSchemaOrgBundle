<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer;

class PageTreeRouteTransformer implements TransformerInterface
{
    public function transform($value)
    {
        return $value['path'];
    }
}
