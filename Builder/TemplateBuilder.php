<?php

declare(strict_types=1);

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Builder;

use Spatie\SchemaOrg\BaseType;
use Spatie\SchemaOrg\Schema;
use Sulu\Component\Content\Compat\Structure\StructureBridge;
use Sulu\Component\Content\Compat\StructureInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer\SchemaOrgAnalyzerInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Exception\SchemaException;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Mapper\PropertyMapper;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Model\SchemaModel;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer\TransformerChain;

class TemplateBuilder implements SchemaOrgBuilderInterface
{
    private PropertyMapper $propertyMapper;
    private TransformerChain $transformerChain;
    private array $config;

    public function __construct(
        PropertyMapper $propertyMapper,
        TransformerChain $transformerChain,
        array $config
    ) {
        $this->propertyMapper = $propertyMapper;
        $this->transformerChain = $transformerChain;
        $this->config = $config;
    }

    /**
     * @param string $key
     * @param StructureInterface $data
     * @return BaseType
     *
     * @throws SchemaException
     */
    public function build(string $key, $data): BaseType
    {
        $model = new SchemaModel($key);

        $propierties = $data->getProperties(true);
        $this->propertyMapper->parseProperties($propierties, $model);

        if ($data instanceof StructureBridge) {
            $extensions = $data->getExt()->toArray();

            foreach ($this->config as $ext => $mapping) {
                $extensionData = $extensions[$ext];
                if ($mapping = $mapping[$key] ?? ($mapping['default'] ?? null)) {
                    $this->extensionMapping($model, $mapping, $extensionData);
                }
            }
        }
        return $model->buildSchema();
    }

    public function support(string $key): bool
    {
        return method_exists(Schema::class, $key);
    }

    private function extensionMapping(SchemaModel $model, array $mapping, array $extensionData): void
    {
        foreach ($mapping as $field => $data) {
            $property = $data['property'];
            $value = $extensionData[$field];
            $value = $this->transformerChain->transform($data['type'], $value);
            $model->setProperty($property, $value);
        }
    }
}
