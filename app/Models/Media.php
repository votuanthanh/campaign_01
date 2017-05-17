<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'url',
        'type',
        'mediable_id',
        'mediable_type',
    ];

    protected $dates = ['deleted_at'];

    public function mediable()
    {
        return $this->morphTo();
    }
}
