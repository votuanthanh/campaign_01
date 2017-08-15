<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('base64image', function($attribute, $value, $parameters, $validator) {
            // check ìf file is image
            if (exif_imagetype($value)) {
                return true;
            }

            $base64 = explode(';', $value);
            $type = explode('/', $base64[0]);
            $allow = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

            // check file format
            if (!in_array($type[1], $allow)) {
                return false;
            }

            // check base64 format
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', explode(',', $base64[1])[1])) {
                return false;
            }

            return true;
        });

        Validator::extend('base64url', function($attribute, $value, $parameters, $validator) {
            // check ìf file is image
            if (!is_string($value) && exif_imagetype($value)) {
                return true;
            }

            $str = explode('images', $value);

            if (!empty($str[1])) {
                $str = explode('?', $str[1]);

                return \Storage::disk('image')->has($str[0]);
            }

            $base64 = explode(';', $value);
            $type = explode('/', $base64[0]);
            $allow = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

            // check file format
            if (empty($type[1]) || !in_array($type[1], $allow)) {
                return false;
            }

            // check base64 format
            if (empty($base64[1]) || !preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', explode(',', $base64[1])[1])) {
                return false;
            }

            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
