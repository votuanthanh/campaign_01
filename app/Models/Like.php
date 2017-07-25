<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    protected $appends = ['user'];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likeable()
    {
        return $this->morphTo();
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activitiable');
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }
}
