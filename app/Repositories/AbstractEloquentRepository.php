<?php

namespace App\Repositories;

use App\Enums\State;
use App\Models\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Contracts\AbstractEloquentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as Paginator;

abstract class AbstractEloquentRepository implements AbstractEloquentRepositoryInterface
{
    protected Base $model;

    public function __construct(Base $model)
    {
        $this->model = $model;
    }

    public function getFirstBy(string $key, $value): ?Base
    {
        return $this->model->where($key, $value)->first();
    }

    public function getByUuid(string $uuid): ?Base
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function getFirstByOrFail(string $key, $value): ?Base
    {
        return $this->model->where($key, $value)->firstOrFail();
    }

    public function getByUuidOrFail(string $uuid): ?Base
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    public function getBy(string $key, mixed $value, int $limit = 0): Collection
    {
        if ($limit !== 0) {
            return $this->model->where($key, $value)->take($limit)->get();
        }

        return $this->model->where($key, $value)->get();
    }

    public function getByIn(string $key, array $values): Collection
    {
        return $this->model->whereIn($key, $values)->get();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getModel(): Base
    {
        return $this->model;
    }

    public function getAllRecordsQuery(): Builder
    {
        return $this->model->query();
    }

    public function getActive(): Collection
    {
        return $this->model->newModelQuery()->where('state', State::ACTIVE->value)->get();
    }

    public function activate(Base $model): bool
    {
        return $model->fill(['state' => 'active'])->save();
    }

    public function deactivate(Base $model): bool
    {
        return $model->fill(['state' => 'inactive'])->save();
    }

    public function getWithRelation(array $relations): Collection
    {
        return $this->model->with($relations)->get();
    }

    public function queryWithRelations(array $relations = []): Builder
    {
        return $this->model->with($relations);
    }

    public function active(\Illuminate\Database\Query\Builder $query = null): \Illuminate\Database\Query\Builder
    {
        if (isset($this->model)) {
            return $this->model->where('state', State::ACTIVE->value);
        }

        return $query->where('state', State::ACTIVE->value);
    }

    public function inactive(\Illuminate\Database\Query\Builder $query = null): \Illuminate\Database\Query\Builder
    {
        if (isset($this->model)) {
            return $this->model->where('state', State::INACTIVE->value);
        }

        return $query->where('state', State::INACTIVE->value);
    }
}
