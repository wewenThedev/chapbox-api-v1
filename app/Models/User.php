<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    use HasFactory, SoftDeletes;

    protected $fillable = ['firstname', 'lastname', 'username', 'phone', 'email', 'password', 'profile_id', 'picture_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function picture()
    {
        return $this->belongsTo(Media::class, 'picture_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')->withPivot('sent_at');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
