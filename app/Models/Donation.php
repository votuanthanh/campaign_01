<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends BaseModel
{
    use SoftDeletes;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'value',
        'donation_type_id',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function donationType()
    {
        return $this->belongsTo(DonationType::class);
    }
}
