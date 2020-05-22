<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Factory;

use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Contracts\OrganizationContract;
use Spatie\SchemaOrg\EducationalOrganization;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer\SchemaOrgAnalyzerInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Builder\SchemaOrgBuilderInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Extension\ExtensionChain;
use TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation\SchemaAttributes;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Mapper\AutoMapper;

class SchemaOrgFactory
{
    const TWIG_KEY = '<!-- SCHEMAS -->';

    /**
     * @var SchemaOrgBuilderInterface[]
     */
    private array $builders;

    private SchemaAttributes $attributes;
    private ExtensionChain $extensionChain;

    public function __construct(
        SchemaAttributes $attributes,
        ExtensionChain $extensionChain
    ) {
        $this->attributes = $attributes;
        $this->extensionChain = $extensionChain;
    }

    public function addBuilder(SchemaOrgBuilderInterface $builders): void
    {
        $this->builders[] = $builders;
    }

    public function build(Request $request, Response $response): void
    {
        /** @var BaseType[] $schemas */
        $schemas = [];
        foreach ($this->attributes->getAttributes() as $key => $attributes) {
            foreach ($this->builders as $builder) {
                if ($builder->support($key)) {
                    foreach ($attributes as $attribute) {
                        $schemas[$key] = $builder->build($key, $attribute);
                    }
                }
            }
        }

        foreach ($schemas as $schema) {
            $this->extensionChain->extend($schema, $schemas);
        }

        if ($content = $response->getContent()) {
            $content = $this->appendSchemas($schemas, $content);
            $response->setContent($content);
        }
    }

    private function appendSchemas(array $schemas, string $content): string
    {
        $data = '';
        foreach ($schemas as $schema) {
            $data .= $schema->toScript();
        }
        $pos = strripos($content, self::TWIG_KEY);
        // Not pos for Web debug toolbar
        if (!$pos) {
            return $content;
        }
        return substr($content, 0, $pos).$data.substr($content, $pos);
    }
}
