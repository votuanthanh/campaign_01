<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const JOIN = 'join';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'user_id',
        'name',
        'activitiable_id',
        'activitiable_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activitiable()
    {
        return $this->morphTo();
    }
}
