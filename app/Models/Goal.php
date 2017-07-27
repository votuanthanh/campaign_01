<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'event_id',
        'donation_type_id',
        'goal',
    ];
    protected $dates = ['deleted_at'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function donationType()
    {
        return $this->belongsTo(DonationType::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
