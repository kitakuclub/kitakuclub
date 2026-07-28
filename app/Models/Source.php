<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SourceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'external_id'])]
#[Hidden(['sourceable_type', 'sourceable_id'])]
class Source extends Model
{
    /** @use HasFactory<SourceFactory> */
    use HasFactory;

    public function equals(self $other): bool
    {
        return $this->is($other);
    }
}
