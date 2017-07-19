<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Action extends BaseModel
{
    use SoftDeletes, SearchableTrait;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'caption',
        'description',
        'user_id',
        'event_id',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['comments'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activitiable');
    }

    public function getCommentsAttribute()
    {
        return $this->comments()
            ->where('parent_id', config('settings.comment_parent'))
            ->orderBy('created_at', 'desc')
            ->paginate(config('settings.paginate_comment'));
    }

    protected $searchable = [
        'columns' => [
            'actions.caption' => 10,
            'actions.description' => 10,
            'users.name' => 9,
        ],
        'joins' => [
            'users' => ['actions.user_id', 'users.id'],
        ],
    ];
}
