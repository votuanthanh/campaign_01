<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'user_id',
        'event_id',
        'goal_id',
        'cost',
        'time',
        'reason',
        'type',
    ];

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->whereNull('expense_product.deleted_at')
            ->withPivot('quantity', 'quality_id')
            ->withTimestamps();
    }

    public function qualitys()
    {
        return $this->belongsToMany(Quality::class, 'expense_product')
            ->withTimestamps();
    }
}
