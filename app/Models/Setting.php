<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'key',
        'value',
    ];

    protected $dates = ['deleted_at'];

    public function settingable()
    {
        return $this->morphTo();
    }
}
