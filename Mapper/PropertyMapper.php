<?php

declare(strict_types=1);

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Mapper;

use Sulu\Component\Content\Compat\Block\BlockProperty;
use Sulu\Component\Content\Compat\PropertyInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer\SchemaOrgAnalyzerInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Exception\SchemaException;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Model\SchemaModel;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer\TransformerChain;

class PropertyMapper
{
    private TransformerChain $transformerChain;

    public function __construct(TransformerChain $transformerChain)
    {
        $this->transformerChain = $transformerChain;
    }

    /**
     * @param PropertyInterface[] $properties
     * @param SchemaModel $model
     *
     * @throws SchemaException
     */
    public function parseProperties(array $properties, SchemaModel $model): void
    {
        foreach ($properties as $property) {
            if ($property instanceof BlockProperty) {
                for ($i = 0; $i < $property->getLength(); $i++) {
                    $this->parseProperties($property->getProperties($i)->getChildProperties(), $model);
                }
            }
            /** @phpstan-ignore-next-line */
            if (!$property->hasTag(SchemaOrgAnalyzerInterface::TAG)) {
                continue;
            }
            $tag = $property->getTag(SchemaOrgAnalyzerInterface::TAG);
            $attributes = $tag->getAttributes();

            if (!$schemaProperty = $this->getPropertyFromAttributes($attributes, $property, $model)) {
                continue;
            }

            $value = $this->transformerChain->transform($property->getContentTypeName(), $property->getValue());
            $model->setProperty($schemaProperty, $value);
        }
    }

    /**
     * @throws SchemaException
     */
    private function getPropertyFromAttributes(array $attributes, PropertyInterface $property, SchemaModel $model): ?string
    {
        if (!array_key_exists('itemtype', $attributes)) {
            throw new SchemaException(sprintf("Missing 'itemtype' attribute in %s", $property->getName()));
        }
        if (!array_key_exists('itemprop', $attributes)) {
            throw new SchemaException(sprintf("Missing 'property' attribute in %s", $property->getName()));
        }
        $type = $attributes['itemtype'];

        // Check if property is not in schema scope
        if ($type !== '*' && $type !== $model->getSchemaType()) {
            return null;
        }
        return $attributes['itemprop'];
    }
}
