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

    public function settingable()
    {
        return $this->morphTo();
    }
}
