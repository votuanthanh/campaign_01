<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];

    public function expenses()
    {
        return $this->belongsToMany(Expense::class)
            ->withPivot('quantity', 'quality_id')
            ->withTimestamps();
    }
}
