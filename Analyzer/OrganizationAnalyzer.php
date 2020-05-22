<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Analyzer;

use Sulu\Bundle\ContactBundle\Entity\AccountInterface;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use TheCocktail\Bundle\SuluSchemaOrgBundle\Builder\OrganizationBuilder;
use TheCocktail\Bundle\SuluSchemaOrgBundle\HttpFoundation\SchemaAttributes;
use Sulu\Bundle\ContactBundle\Entity\AccountRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class OrganizationAnalyzer implements SchemaOrgAnalyzerInterface
{
    private AccountRepositoryInterface $repository;
    private SchemaAttributes $attributes;

    public function __construct(
        AccountRepositoryInterface $repository,
        SchemaAttributes $attributes
    ) {
        $this->repository = $repository;
        $this->attributes = $attributes;
    }

    public function analyze(Request $request): void
    {
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if ($account = $this->repository->findOneBy(['uid' => 'main'])) {
            $this->attributes->addAttribute(OrganizationBuilder::KEY, $account);
        }
    }
}
