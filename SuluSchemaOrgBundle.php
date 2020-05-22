<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler\ExtensionPass;
use TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler\AnalyzerPass;
use TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler\BuilderPass;
use TheCocktail\Bundle\SuluSchemaOrgBundle\DependencyInjection\Compiler\TransformerPass;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Extension\ExtensionInterface;

/**
 * Entry-point for university-bundle.
 */
class SuluSchemaOrgBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->registerForAutoconfiguration(ExtensionInterface::class)
            ->addTag('sulu.schema_org.extension')
        ;

        $container->addCompilerPass(new AnalyzerPass());
        $container->addCompilerPass(new BuilderPass());
        $container->addCompilerPass(new ExtensionPass());
        $container->addCompilerPass(new TransformerPass());
    }
}
