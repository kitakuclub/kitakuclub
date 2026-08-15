<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;

class AnimeSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = Anime::query();

        $q->orderBy('aired_at', 'desc');
        $q->orderBy('created_at');

        foreach ($request->except(['page']) as $key => $value) {
            $q->where(match ($key) {
                'season' => 'aired_season',
                'year' => 'aired_year',
                'mpaa' => 'rating',
                default => $key,
            }, $value);
        }

        $animes_list = $q->paginate(32)->withQueryString();

        return view('web.animes.search', compact('animes_list'));
    }
}
