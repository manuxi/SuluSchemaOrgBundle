<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer;

class TransformerChain
{
    /**
     * @var TransformerInterface[]
     */
    private array $transformers;

    public function addTransformer(string $alias, TransformerInterface $transformer): void
    {
        $this->transformers[$alias] = $transformer;
    }

    /**
     * @param string $contentTypeName
     * @param mixed $value
     * @return mixed
     */
    public function transform(string $contentTypeName, $value)
    {
        if (!array_key_exists($contentTypeName, $this->transformers)) {
            return $this->transformers['simple']->transform($value);
        }
        return $this->transformers[$contentTypeName]->transform($value);
    }
}
