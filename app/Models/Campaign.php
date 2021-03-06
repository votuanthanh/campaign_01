<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'hashtag',
        'title',
        'description',
        'slug',
        'longitude',
        'latitude',
        'status',
    ];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function settings()
    {
        return $this->morphMany(Setting::class, 'settingable');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
