<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface BaseInterface
{
    function scopeOrdered(Builder $query, string $orderBy = 'id', string $order = 'asc'): Builder;
}
