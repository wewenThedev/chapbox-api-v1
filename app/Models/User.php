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

    protected $fillable = ['firstname', 'lastname', 'username', 'phone', 'email', 'password', 'profile_id', 'picture_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function picture()
    {
        return $this->belongsTo(Media::class, 'picture_id');
    }

    public function cart()
    {
        //return $this->belongsTo(Cart::class, 'user_id');
        return $this->hasOne(Cart::class, 'user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_manager_id');
    }

    public function supermarket()
    {
        return $this->belongsTo(Supermarket::class, 'market_manager_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications')->withPivot('sent_at');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
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

    public function isRole(string $role): bool
    {
        if ($this->profile->name === $role) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the user's most recent order.
     */
    public function latestOrder()
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }

    /**
     * Get the user's oldest order.
     */
    public function oldestOrder()
    {
        return $this->hasOne(Order::class)->oldestOfMany();
    }

    /**
 * Get the user's largest order.
 */
public function largestOrder()
{
    return $this->orders()->one()->ofMany('price', 'max');
}
    /*public function isCartExist(?string $device_id){
        if($device_id!==null || $device_id!==''){
            if(Cart::where('device_id', $device_id)){
                return true;
            }
        }else{
            return false;
        }
    }*/
}
