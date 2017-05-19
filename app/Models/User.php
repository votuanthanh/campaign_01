<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        return $this->belongsToMany(Campaign::class);
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
}
