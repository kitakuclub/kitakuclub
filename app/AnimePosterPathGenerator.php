<?php

declare(strict_types=1);

namespace App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * @todo move, some day
 */
class AnimePosterPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return '/poster/animes/' . $media->model_id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . '/responsives/';
    }
}
