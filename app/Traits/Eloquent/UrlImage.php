<?php
namespace App\Traits\Eloquent;

trait UrlImage
{
    public function getImageDefaultAttribute($value)
    {
        return app('glide.url')->getUrl($this->url_file);
    }

    public function getImageThumbnailAttribute($value)
    {
        return app('glide.url')->getUrl($this->url_file, ['p' => 'thumbnail']);
    }

    public function getImageSmallAttribute($value)
    {
        return app('glide.url')->getUrl($this->url_file, ['p' => 'small']);
    }

    public function getImageMediumAttribute($value)
    {
        return app('glide.url')->getUrl($this->url_file, ['p' => 'medium']);
    }

    public function getImageLargeAttribute($value)
    {
        return app('glide.url')->getUrl($this->url_file, ['p' => 'large']);
    }
}
