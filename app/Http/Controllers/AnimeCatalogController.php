<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;

class AnimeCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $q = Anime::query();

        $q->orderBy('aired_at', 'desc');
        $q->orderBy('created_at');

        $animes_list = $q->paginate(32);

        return view('web.animes.catalog', compact('animes_list'));
    }
}
