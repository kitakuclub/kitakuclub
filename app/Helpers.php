<?php

declare(strict_types=1);

use Illuminate\Support\Str;

function kodik_title(string $value): string
{
    return Str::before($value, '.Subtitles');
}

function kodik_type(string $value): string
{
    return Str::endsWith($value, '.Subtitles') ? 'subtitles' : 'voice';
}
