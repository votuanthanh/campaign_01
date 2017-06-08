<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;

    const TYPE_CAMPAIGN = 2;
    const TYPE_SYSTEM = 1;
    const ROLE_USER = 'user';
    const ROLE_OWNER = 'owner';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
