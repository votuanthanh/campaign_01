<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Quality extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'name',
    ];

    protected $dates = ['deleted_at'];

    public function donationTypes()
    {
        return $this->hasMany(DonationType::class);
    }
}
