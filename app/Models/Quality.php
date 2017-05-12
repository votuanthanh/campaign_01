<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Quality extends BaseModel
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
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
