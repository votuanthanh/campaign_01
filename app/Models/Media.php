<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    const IMAGE = 0;
    const VIDEO = 1;
    
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

    public function mediable()
    {
        return $this->morphTo();
    }
}
