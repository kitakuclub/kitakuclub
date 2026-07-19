<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

/** @template T of Model */
interface Repository
{
    /** @return T */
    public function getOne($id): Model;
}
