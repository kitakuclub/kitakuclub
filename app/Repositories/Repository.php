<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Contracts\Repository as RepositoryContract;
use Illuminate\Database\Eloquent\Model;

/**
 * @template T of Model
 * @implements RepositoryContract<T>
 */
abstract class Repository implements RepositoryContract
{
    /** @var class-string<T> $modelClass */
    public string $modelClass;

    /** @param class-string<T> $modelClass */
    public function __construct(?string $modelClass = null)
    {
        $this->modelClass = $modelClass ?: self::guessModelClass();
    }

    /** @return class-string<T> */
    private static function guessModelClass(): string
    {
        return preg_replace('/(.+)\\\\Repositories\\\\(.+)Repository$/m', '$1\Models\\\$2', static::class);
    }

    /** @inheritDoc */
    public function getOne($id): Model
    {
        return $this->modelClass::query()->findOrFail($id);
    }
}
