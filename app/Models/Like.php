<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'user_id',
        'target_id',
        'target_type',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likeable()
    {
        return $this->morphTo();
    }
}
