<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;

    const TYPE_CAMPAIGN = 'campaign';
    const TYPE_SYSTEM = 'system';
    const ROLE_USER = 'user';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'name',
        'type_id',
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

    public function scopeUserRole($query)
    {
        return $query->join('types', 'types.id', 'roles.type_id')->where([
            'roles.name' => static::ROLE_USER,
            'types.name' => static::TYPE_SYSTEM,
        ]);
    }
}
