<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtensionPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('sulu.schema_org.extension_chain')) {
            return;
        }

        $definition = $container->findDefinition('sulu.schema_org.extension_chain');
        $warmers = $this->findAndSortTaggedServices('sulu.schema_org.extension', $container);

        foreach ($warmers as $reference) {
            $definition->addMethodCall('addExtension', [$reference]);
        }
    }
}
