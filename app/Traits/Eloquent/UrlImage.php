<?php
namespace App\Traits\Eloquent;

trait UrlImage
{
    public function getImageDefaultAttribute($value)
    {
        return $this->factoryImage($this->url_file);
    }

    public function getImageThumbnailAttribute($value)
    {
        return $this->factoryImage($this->url_file, 'thumbnail');
    }

    public function getImageSmallAttribute($value)
    {
        return $this->factoryImage($this->url_file, 'small');
    }

    public function getImageMediumAttribute($value)
    {
        return $this->factoryImage($this->url_file, 'medium');
    }

    public function getImageLargeAttribute($value)
    {
        return $this->factoryImage($this->url_file, 'large');
    }

    public function getImageSliderAttribute($value)
    {
        return $this->factoryImage($this->url_file, 'slider');
    }

    private function factoryImage($value, $type = '')
    {
        return preg_match('#^(http)|(https).*$#', $value)
            ? $value
            : app('glide.url')->getUrl($value, $type ? ['p' => $type] : []);
    }
}
