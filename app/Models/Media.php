<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'url',
        'type',
    ];

    protected $dates = ['deleted_at'];

    public function mediable()
    {
        return $this->morphTo();
    }
}
