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

    protected $hidden = [
        'calculate',
    ];

    protected $appends = [
        'calculate',
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

    public function getCalculateAttribute()
    {
        $calculate = new \stdClass();
        $calculate->donation_sum = $this->donations()->whereStatus(Donation::ACCEPT)->sum('value');
        $calculate->donation_count = $this->donations()->whereStatus(Donation::ACCEPT)->count();
        $calculate->expense_sum = $this->expenses()->sum('cost');
        $calculate->expense_count = $this->expenses()->count();

        return $calculate;
    }
}
