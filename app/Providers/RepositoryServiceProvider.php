<?php

namespace App\Providers;

use App\Contracts\PropertyRepositoryInterface;
use App\Repositories\PropertyRepository;
use App\Models\Property;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            PropertyRepositoryInterface::class,
            fn() => new PropertyRepository(new Property)
        );
    }
}