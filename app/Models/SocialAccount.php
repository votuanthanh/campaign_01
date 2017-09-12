<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAccount extends BaseModel
{
    use SoftDeletes;

    const FRAMGIA_PROVIDER = 'framgia';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'user_id',
        'provider_id',
        'email',
        'name',
        'provider',
        'url_file',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
