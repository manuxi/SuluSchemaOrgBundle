<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer\SchemaOrgAnalyzerInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation\SchemaAttributes;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SchemaOrgExtension extends AbstractExtension
{
    private SchemaAttributes $attributes;

    public function __construct(SchemaAttributes $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('sulu_schema_org', [$this, 'buildSchema'])
        ];
    }

    /**
     * @param string $key
     * @param mixed $data
     */
    public function buildSchema(string $key, $data): void
    {
        $this->attributes->addAttribute($key, $data);
    }
}
