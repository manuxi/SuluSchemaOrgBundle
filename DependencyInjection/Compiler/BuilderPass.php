<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BuilderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sulu.schema_org.factory')) {
            return;
        }

        $definition = $container->findDefinition('sulu.schema_org.factory');
        $warmers = $this->findAndSortTaggedServices('sulu.schema_org.builder', $container);

        foreach ($warmers as $reference) {
            $definition->addMethodCall('addBuilder', [$reference]);
        }
    }
}
