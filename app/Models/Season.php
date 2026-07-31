<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Seasons\HasSeasonRelationships;
use Database\Factories\SeasonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['number', 'link'])]
class Season extends Model
{
    /** @use HasFactory<SeasonFactory> */
    use HasFactory, HasSeasonRelationships;
}
