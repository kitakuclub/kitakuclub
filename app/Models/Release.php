<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\Releases\HasReleaseRelationships;
use Database\Factories\ReleaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Funteam $funteam
 */
#[Fillable(['translation_id', 'external_id', 'link'])]
#[Hidden(['translation_id', 'releasable_type', 'releasable_id'])]
class Release extends Model
{
    /** @use HasFactory<ReleaseFactory> */
    use HasFactory, HasReleaseRelationships;

    protected $with = ['translation.funteam'];

    public function getFunteamAttribute()
    {
        return $this->translation->funteam;
    }
}
