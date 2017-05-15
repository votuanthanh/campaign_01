<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'name',
    ];

    protected $dates = ['deleted_at'];

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class);
    }
}
