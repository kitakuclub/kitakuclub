<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EpisodeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['translation_id', 'external_id', 'number', 'link'])]
class Episode extends Model
{
    /** @use HasFactory<EpisodeFactory> */
    use HasFactory;
}
