<?php

namespace App\Contracts;

use App\Models\Base;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface AbstractEloquentRepositoryInterface
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Base|null
     */
    public function getFirstBy(string $key, $value): ?Base;

    /**
     * @param string $uuid
     *
     * @return Base|null
     */
    public function getByUuid(string $uuid): ?Base;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Base|null
     */
    public function getFirstByOrFail(string $key, $value): ?Base;

    /**
     * @param string $uuid
     *
     * @return Base|null
     */
    public function getByUuidOrFail(string $uuid): ?Base;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return Collection
     */
    public function getBy(string $key, mixed $value): Collection;

    public function getByIn(string $key, array $values): Collection;

    /**
     * @param Base $model
     *
     * @return bool
     */

    /**
     * @param array $relations
     *
     * @return Builder
     */
    public function queryWithRelations(array $relations = []): Builder;

    /**
     * @return Builder
     */
    public function getAllRecordsQuery(): Builder;


    public function activate(Base $model): bool;

    /**
     * @param Base $model
     *
     * @return bool
     */
    public function deactivate(Base $model): bool;

    public function getActive(): Collection;

    public function getModel(): Base;

    public function active(\Illuminate\Database\Query\Builder $query = null): \Illuminate\Database\Query\Builder;

    public function inactive(\Illuminate\Database\Query\Builder $query = null): \Illuminate\Database\Query\Builder;
}
