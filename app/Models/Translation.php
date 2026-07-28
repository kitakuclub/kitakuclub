<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Translations\HasTranslationRelationships;
use Database\Factories\TranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['funteam_id', 'source', 'external_id', 'kind', 'locale'])]
#[Hidden(['pivot', 'funteam_id'])]
class Translation extends Model
{
    /** @use HasFactory<TranslationFactory> */
    use HasFactory, HasTranslationRelationships;

    protected $with = ['funteam'];
}
