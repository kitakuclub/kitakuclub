<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Services\Contracts\AnimeFeedService as AnimeFeedServiceContract;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LatestReleasesAnime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private readonly AnimeFeedServiceContract $feed
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $releases = $this->feed->latestReleases(10);

        return view('components.latest-releases-anime', compact('releases'));
    }
}
