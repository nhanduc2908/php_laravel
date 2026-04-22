<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'user_sessions';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'token', 'ip', 'user_agent', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}