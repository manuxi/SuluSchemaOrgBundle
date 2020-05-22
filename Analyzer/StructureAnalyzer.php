<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer;

use Spatie\SchemaOrg\Schema;
use Sulu\Component\Content\Compat\Structure\PageBridge;
use Sulu\Component\Content\Compat\Structure\StructureBridge;
use Sulu\Component\Content\Compat\StructureInterface;
use Sulu\Component\Content\Metadata\StructureMetadata;
use Symfony\Component\HttpFoundation\Request;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Exception\SchemaTypeNotFound;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Exception\SchemaTypeNotImplemented;
use TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation\SchemaAttributes;

class StructureAnalyzer implements SchemaOrgAnalyzerInterface
{
    private SchemaAttributes $attributes;

    public function __construct(SchemaAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

    public function analyze(Request $request): void
    {
        $structure = $request->attributes->get('structure');

        if (!$structure instanceof StructureBridge) {
            return;
        }

        $metadata = $structure->getStructure();

        if (!$metadata->hasTag(SchemaOrgAnalyzerInterface::TAG)) {
            return;
        }

        $tag = $metadata->getTag(SchemaOrgAnalyzerInterface::TAG);
        $type = $tag['attributes']['itemtype'] ?? null;

        if (!$type) {
            throw new SchemaTypeNotFound(sprintf("Property 'itemtype' not found in %s", $metadata->getName()));
        }

        if (!method_exists(Schema::class, $type)) {
            throw new SchemaTypeNotImplemented(sprintf('Schema %1$s not implemented, check https://schema.org/%1$s', $type));
        }

        $this->attributes->addAttribute($type, $structure);
    }
}
