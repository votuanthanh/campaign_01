<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }

    protected $fillable = [
        'id',
        'name',
        'type_id',
    ];

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
