<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected static $repositories = [
        'user' => [
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class,
        ],
        'role' => [
            \App\Repositories\Contracts\RoleInterface::class,
            \App\Repositories\Eloquent\RoleRepository::class,
        ],
        'type' => [
            \App\Repositories\Contracts\TypeInterface::class,
            \App\Repositories\Eloquent\TypeRepository::class,
        ],
        'campaign' => [
            \App\Repositories\Contracts\CampaignInterface::class,
            \App\Repositories\Eloquent\CampaignRepository::class,
        ],
        'tag' => [
            \App\Repositories\Contracts\TagInterface::class,
            \App\Repositories\Eloquent\TagRepository::class,
        ],
        'event' => [
            \App\Repositories\Contracts\EventInterface::class,
            \App\Repositories\Eloquent\EventRepository::class,
        ],
        'quality' => [
            \App\Repositories\Contracts\QualityInterface::class,
            \App\Repositories\Eloquent\QualityRepository::class,
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (static::$repositories as $repository) {
            $this->app->singleton(
                $repository[0],
                $repository[1]
            );
        }
    }
}
