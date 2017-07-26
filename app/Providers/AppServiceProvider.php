<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Storage;
use League\Glide\Urls\UrlBuilderFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGlide();
        $this->registerUrlBuilder();
    }

    protected function registerGlide()
    {
        $this->app->singleton('glide', function ($app) {
            return ServerFactory::create([
                'source' => Storage::disk('image')->getDriver(),
                'cache' => Storage::disk('image')->getDriver(),
                'cache_path_prefix' => 'cache',
                'base_url' => null,
                'max_image_size' => 2000 * 2000,
                'presets' => [
                    'thumbnail' => [
                        'w' => 100,
                        'h' => 100,
                        'fit' => 'max',
                    ],
                    'small' => [
                        'w' => 320,
                        'h' => 240,
                        'fit' => 'max',
                    ],
                    'medium' => [
                        'w' => 640,
                        'h' => 480,
                        'fit' => 'max',
                    ],
                    'large' => [
                        'w' => 800,
                        'h' => 600,
                        'fit' => 'max',
                    ],
                    'slider' => [
                        'w' => 900,
                        'h' => 450,
                        'fit' => 'max',
                    ],
                ],
                'response' => new LaravelResponseFactory(app('request')),
            ]);
        });
    }

    protected function registerUrlBuilder()
    {
        $this->app->singleton('glide.url', function ($app) {
            return UrlBuilderFactory::create('/images/');
        });
    }
}
