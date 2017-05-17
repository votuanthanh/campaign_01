<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DonationType extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'name',
        'quality_id',
    ];

    protected $dates = ['deleted_at'];

    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
