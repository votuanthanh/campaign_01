<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'key',
        'value',
        'settingable_id',
        'settingable_type',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['date_string'];

    public function settingable()
    {
        return $this->morphTo();
    }

    public function getDateStringAttribute()
    {
        if (!in_array($this->key, [
            config('settings.campaigns.start_day'),
            config('settings.campaigns.end_day'),
            config('settings.events.start_day'),
            config('settings.events.end_day'),
        ])) {
            return null;
        }

        return \Carbon\Carbon::parse($this->value)->toDateString();
    }
}
