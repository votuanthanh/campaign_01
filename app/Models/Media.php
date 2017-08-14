<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Eloquent\UrlImage;

class Media extends BaseModel
{
    use SoftDeletes, UrlImage;

    const IMAGE = 0;
    const VIDEO = 1;
    const EDITOR_QUILL = 2;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'url_file',
        'type',
        'mediable_id',
        'mediable_type',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'image_default',
        'image_medium',
        'image_small',
        'image_thumbnail',
        'image_large',
        'image_slider',
        'image_large',
        'time_ago'
    ];

    public function getTimeAgoAttribute($date)
    {
        $locale = \App::getLocale();
        \Carbon\Carbon::setLocale($locale);

        return \Carbon\Carbon::parse($date)->diffForHumans();
    }

    public function mediable()
    {
        return $this->morphTo();
    }
}
