<?php

namespace Spatie\MediaLibrary\Support\UrlGenerator;

use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidUrlGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

class UrlGeneratorFactory
{
    public static function createForMedia(Media $media, string $conversionName = ''): UrlGenerator
    {
        $urlGeneratorClass = config('media-library.url_generator');
        static::guardAgainstInvalidUrlGenerator($urlGeneratorClass);

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = app($urlGeneratorClass);
        $pathGenerator = PathGeneratorFactory::create($media);
        $urlGenerator->setMedia($media)->setPathGenerator($pathGenerator);
        if ($conversionName !== '') {
            $urlGenerator->setConversionName($conversionName);
        }

        return $urlGenerator;
    }

    public static function guardAgainstInvalidUrlGenerator(string $urlGeneratorClass): void
    {
        if (! class_exists($urlGeneratorClass)) {
            throw InvalidUrlGenerator::doesntExist($urlGeneratorClass);
        }

        if (! is_subclass_of($urlGeneratorClass, UrlGenerator::class)) {
            throw InvalidUrlGenerator::doesNotImplementUrlGenerator($urlGeneratorClass);
        }
    }
}
