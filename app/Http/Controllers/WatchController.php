<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Release;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        /** @var Release $release */
        $release = Release::query()->where('code', $request->r)->firstOrFail();

        /** @var Anime $anime */
        $anime = $release->releasable;

        return view('web.watch', compact('release', 'anime'));
    }
}
