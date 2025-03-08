<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserNotification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'notification_id'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notification(){
        return $this->belongsTo(Notification::class, 'notification_id');;
    }
}
