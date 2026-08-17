<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\EntryStatus;
use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class NewestAnime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $items = Anime::query()->latestReleases();

        return view('components.newest-anime', compact('items'));
    }
}
