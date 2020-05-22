<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer;

use Symfony\Component\HttpFoundation\Request;
use TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation\SchemaAttributes;

interface SchemaOrgAnalyzerInterface
{
    const TAG = 'sulu.schema_org';

    public function analyze(Request $request): void;
}
