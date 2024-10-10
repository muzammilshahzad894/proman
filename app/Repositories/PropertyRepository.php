<?php

namespace App\Repositories;

use App\Repositories\AbstractEloquentRepository;
use App\Contracts\PropertyRepositoryInterface;
use App\Models\Property;
use App\Models\Base;

class PropertyRepository extends AbstractEloquentRepository implements PropertyRepositoryInterface
{
    protected Base $model;

    public function __construct(Property $model)
    {
        parent::__construct($model);
    }
}
