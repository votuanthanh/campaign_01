<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends BaseModel
{
    use SoftDeletes;

     // Donation's status
    const ACCEPT = 1;
    const NOT_ACCEPT = 0;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'value',
        'campaign_id',
        'goal_id',
        'status',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['donated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function getDonatedAtAttribute()
    {
        $locale = \App::getLocale();
        \Carbon\Carbon::setlocale($locale);

        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }
}
