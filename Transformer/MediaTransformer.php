<?php

namespace TheCocktail\Bundle\SuluSchemaOrgBundle\Transformer;

use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MediaTransformer implements TransformerInterface
{
    private MediaManagerInterface $mediaManager;
    private RequestStack $requestStack;
    private string $imageFormat;

    public function __construct(
        MediaManagerInterface $mediaManager,
        RequestStack $requestStack,
        string $imageFormat
    ) {
        $this->mediaManager = $mediaManager;
        $this->requestStack = $requestStack;
        $this->imageFormat = $imageFormat;
    }

    public function transform($value): string
    {
        if (empty($ids = $value['ids'])) {
            return '';
        }
        $id = reset($ids);

        if (!$request = $this->requestStack->getMasterRequest()) {
            return '';
        }

        $formats = $this->mediaManager->getFormatUrls([$id], $request->getLocale());

        return $formats[$id][$this->imageFormat];
    }
}
