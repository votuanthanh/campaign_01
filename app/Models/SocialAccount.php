<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAccount extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'user_id',
        'provide_id',
        'email',
        'name',
        'provide',
        'avatar',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
