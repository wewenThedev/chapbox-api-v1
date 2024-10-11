<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['message', 'notification_category_id'];

    public function category()
    {
        return $this->belongsTo(NotificationCategory::class, 'notification_category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notification')->withTimestamps();
    }
}
