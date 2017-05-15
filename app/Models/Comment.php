<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'user_id',
        'content',
        'target_id',
        'target_type',
        'parent_id',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
