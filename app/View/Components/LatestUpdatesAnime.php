<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Services\Contracts\AnimeFeedService as AnimeFeedServiceContract;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LatestUpdatesAnime extends Component
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
        $updates = $this->feed->latestUpdates(5);

        return view('components.latest-updates-anime', compact('updates'));
    }
}
