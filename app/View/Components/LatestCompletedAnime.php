<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\EntryStatus;
use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LatestCompletedAnime extends Component
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
        $q = Anime::query();

        $q->where('status', EntryStatus::RELEASED);
        $q->whereNotNull('released_at');
        $q->orderBy('released_at', 'desc');
        $q->limit(5);

        /** @var Collection<Anime> $items */
        $items = $q->get();

        return view('components.latest-completed-anime', compact('items'));
    }
}
