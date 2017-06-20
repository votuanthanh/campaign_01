<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Eloquent\UrlImage;
use App\Models\Role;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Traits\Eloquent\ConvertEmptyStringToNull;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, UrlImage, ConvertEmptyStringToNull;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // User's status
    const IN_ACTIVE = 0;
    const ACTIVE = 1;
    const BAN = 2;
    const ACTIVE_LINK_SEND = 'emails.active';

    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'url_file',
        'address',
        'phone',
        'status',
        'token_confirm',
        'head_photo',
        'gender',
        'about',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class)->withPivot('role_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'relationships', 'user_id', 'following_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'relationships', 'following_id', 'user_id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function hasRole($role)
    {
        $roleId = Role::whereName($role)->first()->id;

        return $this->roles()->wherePivot('role_id', $roleId)->exists();
    }

    public function getHeadPhotoAttribute($value)
    {
        return app('glide.url')->getUrl($value);
    }

    public function getAbout($value = null)
    {
        if (!$value || !is_numeric($value)) {
            return $this->attributes['about'];
        }

        return str_limit($this->attributes['about'], $value);
    }

    public function settings()
    {
        return $this->morphMany(Setting::class, 'settingable');
    }

    protected static function boot()
    {
        static::creating(function ($user) {
            // Set filtering params before creating that it executes saving
            $user->token_confirm = Str::random(60);
            $user->birthday = is_null($user->birthday) ? null : Carbon::parse($user->birthday);
        });
    }
}
