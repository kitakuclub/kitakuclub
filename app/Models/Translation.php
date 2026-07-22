<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['funteam_id', 'source', 'external_id', 'kind', 'locale', 'link'])]
class Translation extends Model
{
    /** @use HasFactory<TranslationFactory> */
    use HasFactory;
}
