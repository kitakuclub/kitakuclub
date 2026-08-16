<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Enums\EntrySeason;
use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CarouselsAnime extends Component
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

        $q->where('aired_year', now()->year);
        $q->where('aired_season', EntrySeason::fromDate(now()));
        $q->orderBy('aired_at', 'desc');

        $items = $q
            ->get(['id', 'name', 'slug'])
            ->map(static fn(Anime $anime) => [
                'title' => $anime->name,
                'image' => $anime->getFirstMediaUrl('poster'),
                'url' => route('animes.show', [$anime, $anime->slug]),
            ]);

        return view('components.carousels-anime', compact('items'));
    }
}
