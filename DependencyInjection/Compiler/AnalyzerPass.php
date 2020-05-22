<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AnalyzerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sulu.schema_org.request_collector')) {
            return;
        }

        $definition = $container->findDefinition('sulu.schema_org.request_collector');
        $taggedServices = $container->findTaggedServiceIds('sulu.schema_org.analyzer');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addAnalyzer', [new Reference($id)]);
        }
    }
}
